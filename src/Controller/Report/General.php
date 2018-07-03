<?php
namespace App\Controller\Report;

use App\AuthValidator;
use App\Entity\Category;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Report;
use App\Entity\Score;
use App\Entity\Wonder;
use App\Grid\Factory as GridFactory;
use App\Grid\Column\Factory as ColumnFactory;
use App\Score\Columns;
use App\Util\StringUtils;
use Doctrine\Common\Persistence\ManagerRegistry;
use FL\QBJSParser\Parser\Doctrine\DoctrineParser;
use FL\QBJSParser\Serializer\JsonDeserializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class General extends AbstractController
{
    const DO_NOT_SAVE = 0;
    const SAVE = 1;
    const UPDATE = 2;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    private $scoreColumns;
    /**
     * @var RequestStack
     */
    private $requestStack;

    private $deserializer;

    private $stringUtils;

    private $gridFactory;

    private $columnFactory;

    private $reportEntity;

    private $authValidator;

    /**
     * General constructor.
     * @param ManagerRegistry $managerRegistry
     * @param Columns $scoreColumns
     * @param RequestStack $requestStack
     * @param JsonDeserializer $deserializer
     * @param StringUtils $stringUtils
     * @param GridFactory $gridFactory
     * @param ColumnFactory $columnFactory
     * @param AuthValidator $authValidator
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Columns $scoreColumns,
        RequestStack $requestStack,
        JsonDeserializer $deserializer,
        StringUtils $stringUtils,
        GridFactory $gridFactory,
        ColumnFactory $columnFactory,
        AuthValidator $authValidator
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->scoreColumns = $scoreColumns;
        $this->requestStack = $requestStack;
        $this->deserializer = $deserializer;
        $this->stringUtils = $stringUtils;
        $this->gridFactory = $gridFactory;
        $this->columnFactory = $columnFactory;
        $this->authValidator = $authValidator;
    }

    /**
     * @return string
     * @Route("/report/general", name="report/general")
     */
    public function execute()
    {

        $decodedRules = [];
        $config = $this->getConfig();
        $gridHtml = '';
        $columns = [];

        $rules = $this->getRules();
        if ($rules) {
            $realDecodedRules = json_decode($rules, true);
            $decodedRules = $this->deserializer->deserialize($rules);
            unset($realDecodedRules['valid']);
            $parser = new DoctrineParser(
                Score::class,
                $this->getFieldsToProperties(),
                [
                    'game' => Game::class,
                    'player' => Player::class,
                    'wonder' => Wonder::class
                ]
            );
            $parsed = $parser->parse($decodedRules);
            $sql = $parsed->getQueryString();
            $config['rules'] = $realDecodedRules;
            $em = $this->managerRegistry->getManager();
            $query = $em->createQuery($sql);
            $query->setParameters($parsed->getParameters());
            $result = $query->getArrayResult();
            $columns = $this->getColumns();
            $grid = $this->getGrid($columns);
            $grid->setRows($query->getResult());
            $gridHtml = $grid->render();
        }
        $report = $this->getReportEntity();
        return $this->render(
            'report/general.html.twig',
            [
                'config' => json_encode($config),
                'fields' => $this->getSelectFields(),
                'grid' => $gridHtml,
                'selected' => $columns,
                'actions' => $this->getActions(),
                'current_report_id' => ($report) ? $report->getId() : '',
                'current_report_name' => ($report) ? $report->getName() : '',
            ]
        );
    }

    /**
     * @return bool|null|Report
     */
    private function getReportEntity()
    {
        if ($this->reportEntity === null) {
            $this->reportEntity = false;
            $reportId = $this->requestStack->getCurrentRequest()->get('report_id');
            if ($reportId) {
                $report = $this->managerRegistry->getRepository(Report::class)->find($reportId);
                if ($report) {
                    $this->reportEntity = $report;
                }
            }
        }
        return $this->reportEntity;
    }

    private function getColumns()
    {
        $columns = $this->requestStack->getCurrentRequest()->get('columns');
        if ($columns) {
            return $columns;
        }
        $report = $this->getReportEntity();
        if ($report) {
            return json_decode($report->getColumns(), true);
        }
        return null;
    }

    private function getRules()
    {
        $rules = $this->requestStack->getCurrentRequest()->get('rules');
        if ($rules) {
            return $rules;
        }
        $report = $this->getReportEntity();
        if ($report) {
            return $report->getRules();
        }
        return null;
    }

    private function getConfig()
    {
        $filters = [
            [
                'id' =>  'game.playedOn',
                'label' => 'Game Date',
                'name' =>  'game.playedOn',
                'input' =>  'text',
                'type' => 'date',
                'plugin' => 'datepicker',
                'plugin_config' => [
                    'dateFormat' =>'yy-mm-dd'
                ],
                'operators' => ['equal','between']
            ],
            [
                'id' =>  'game.leaders',
                'label' => 'Game Played with Leaders',
                'name' =>  'game.leaders',
                'type' => 'integer',
                'input' => 'radio',
                'values' => [
                    1 => 'Yes',
                    0 => 'No'
                ],
                'operators' => ['equal']
            ],
            [
                'id' =>  'game.cities',
                'label' => 'Game Played with Cities',
                'name' =>  'game.cities',
                'type' => 'integer',
                'input' => 'radio',
                'values' => [
                    1 => 'Yes',
                    0 => 'No'
                ],
                'operators' => ['equal']
            ],
            [
                'id' =>  'playerCount',
                'label' => 'Player Count',
                'name' =>  'score.playerCount',
                'type' => 'integer',
                'input' =>  'text',
                'operators' => ['equal','not_equal', 'in', 'not_in', 'less', 'less_or_equal', 'greater',
                    'greater_or_equal', 'between', 'not_between'],
            ],
            [
                'id' =>  'player',
                'label' => 'Player',
                'name' =>  'score.player',
                'input' =>  'select',
                'multiple' => true,
                'operators' => ['in','not_in'],
                'plugin' => 'select2',
                'values' => $this->getPlayers()
            ],
            [
                'id' =>  'wonder',
                'label' => 'Wonder',
                'name' =>  'score.wonder',
                'input' =>  'select',
                'multiple' => true,
                'operators' => ['in','not_in'],
                'plugin' => 'select2',
                'values' => $this->getWonders()
            ],
            [
                'id' =>  'side',
                'label' => 'Side',
                'name' =>  'score.side',
                'input' =>  'select',
                'multiple' => true,
                'operators' => ['in','not_in'],
                'plugin' => 'select2',
                'values' => ['A' => 'A', 'B' => 'B']
            ],
            [
                'id' =>  'rank',
                'label' => 'Rank',
                'name' =>  'score.rank',
                'input' =>  'text',
                'type' => 'integer',
                'operators' => ['equal','not_equal', 'in', 'not_in', 'less', 'less_or_equal', 'greater',
                    'greater_or_equal', 'between', 'not_between', 'is_null', 'is_not_null'],
            ]
        ];
        foreach ($this->scoreColumns->getColumns() as $key => $column) {
            $filters[] = [
                'id' =>  $key,
                'label' => $column['long_label'],
                'name' =>  'score.'.$key,
                'type' => 'integer',
                'input' =>  'text',
                'operators' => ['equal','not_equal', 'in', 'not_in', 'less', 'less_or_equal', 'greater',
                    'greater_or_equal', 'between', 'not_between', 'is_null', 'is_not_null']
            ];
        }
        return ['filters' => $filters];
    }

    private function getFieldsToProperties()
    {
        $props = [
            'side' => 'side',
            'player' => 'player',
            'wonder' => 'wonder',
            'playerCount' => 'playerCount',
            'game.playedOn' => 'game.playedOn',
            'game.leaders' => 'game.leaders',
            'game.cities' => 'game.cities',
            'rank' => 'rank'
        ];
        foreach ($this->scoreColumns->getColumns() as $key => $column) {
            $props[$key] = $this->stringUtils->camelize($key);
        }
        return $props;
    }

    /**
     * @return array
     */
    private function getPlayers()
    {
        /** @var Player[] $players */
        $players = $this->managerRegistry->getRepository(Player::class)->findAll();
        $return = [];
        foreach ($players as $player) {
            $return[$player->getId()] = $player->getName();
        }
        return $return;
    }

    /**
     * @return array
     */
    private function getWonders()
    {
        /** @var Wonder[] $wonders */
        $wonders = $this->managerRegistry->getRepository(Wonder::class)->findAll();
        $return = [];
        foreach ($wonders as $wonder) {
            $return[$wonder->getId()] = $wonder->getName();
        }
        return $return;
    }

    private function getFlatSelectedFields()
    {
        $selectedFields = $this->getSelectFields();
        $flat = [];
        foreach ($selectedFields as $group) {
            $flat = array_merge($flat, $group['fields']);
        }
        return $flat;
    }

    /**
     * @return array
     */
    private function getActions()
    {
        $actions = [];
        if ($this->authValidator->getUser()) {
            $actions[self::DO_NOT_SAVE] = 'Do not save report';
            $currentReport = $this->getReportEntity();
            if ($currentReport) {
                $actions[self::SAVE] = 'Save as New Report';
                $actions[self::UPDATE] = 'Update Existing Report';
            } else {
                $actions[self::SAVE] = 'Save Report';
            }
        }
        return $actions;
    }

    private function getSelectFields()
    {
        $categories = $this->getCategoriesByKey();
        $selectFields = [
            [
                'label' => 'Player',
                'code' => 'player',
                'fields' => [
                    'player' => [
                        'id' => 'player',
                        'long_label' => 'Player',
                        'label' => 'Player',
                        'type' => 'link',
                        'iconClass' => '',
                        'labelKey' => 'getPlayer.getName',
                        'url' => 'player/view',
                        'params' => [
                            'id' => 'getPlayer.getId'
                        ]
                    ],
                    'wonder' => [
                        'id' => 'wonder',
                        'long_label' => 'Wonder',
                        'label' => 'Wonder',
                        'type' => 'link',
                        'iconClass' => '',
                        'labelKey' => 'getWonder.getName',
                        'url' => 'wonder/view',
                        'params' => [
                            'id' => 'getWonder.getId'
                        ]
                    ],
                    'side' => [
                        'id' => 'side',
                        'long_label' => 'Side',
                        'label' => 'Side',
                        'type' => 'text',
                        'iconClass' => '',
                        'index' => 'getSide'
                    ],
                    'rank' => [
                        'id' => 'rank',
                        'long_label' => 'Rank',
                        'label' => 'Rank',
                        'type' => 'integer',
                        'iconClass' => '',
                        'index' => 'getRank'
                    ],
                    'playerCount' => [
                        'id' => 'playerCount',
                        'long_label' => '# Players',
                        'label' => '#',
                        'type' => 'integer',
                        'iconClass' => '',
                        'index' => 'getPlayerCount'
                    ]
                ]
            ],
        ];

        foreach ($categories as $code => $category) {
            $group = [
                'label' => $category->getName(),
                'code' => $category->getCode()
            ];
            $fields = [];
            foreach ($this->scoreColumns->getCategoryColumns($code) as $key => $column) {
                $fields[$key] = [
                    'id' => $key,
                    'long_label' => $column['long_label'],
                    'label' => $column['grid_label'],
                    'type' => 'integer',
                    'iconClass' => isset($categories[$column['category']])
                        ? $category->getIconClass()
                        : '',
                    'index' => $this->stringUtils->camelize('get_'.$key)
                ];
            }
            $group['fields'] = $fields;
            $selectFields[] = $group;
        }
        $selectFields[] = [
            'label' => 'Total Score',
            'code' => 'total',
            'fields' => [
                'total' => [
                    'id' => 'total',
                    'long_label' => 'Total score',
                    'label' => '+',
                    'type' => 'integer',
                    'iconClass' => '',
                    'index' => "getTotalScore"
                ]
            ]
        ];

        return $selectFields;
    }

    private function getGrid($fields)
    {
        $grid = $this->gridFactory->create([
            'title' => 'Report',
            'id' => 'custom-report-grid',
            'emptyMessage' => 'There are no scores matching your selection'
        ]);

        $selectFields = $this->getFlatSelectedFields();
        foreach ($fields as $field) {
            if (isset($selectFields[$field])) {
                $column = $this->columnFactory->create($selectFields[$field]);
                $grid->addColumn($field, $column);
            }
        }

        return $grid;
    }

    /**
     * @return Category[]
     */
    private function getCategoriesByKey()
    {
        $categories = $this->managerRegistry->getRepository(Category::class)->findAll();
        $byKey = [];
        foreach ($categories as $category) {
            /** @var Category $category */
            $byKey[$category->getCode()] = $category;
        }
        return $byKey;
    }
}
