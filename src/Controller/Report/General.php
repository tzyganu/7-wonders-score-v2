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
use App\Report\ColumnsRendererFactory;
use App\Report\Config;
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
    /**
     * @var Columns
     */
    private $scoreColumns;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var JsonDeserializer
     */
    private $deserializer;
    /**
     * @var StringUtils
     */
    private $stringUtils;
    /**
     * @var GridFactory
     */
    private $gridFactory;
    /**
     * @var ColumnFactory
     */
    private $columnFactory;
    /**
     * @var Report
     */
    private $reportEntity;
    /**
     * @var AuthValidator
     */
    private $authValidator;
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ColumnsRendererFactory
     */
    private $columnsRendererFactory;

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
        AuthValidator $authValidator,
        ColumnsRendererFactory $columnsRendererFactory,
        Config $config
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->scoreColumns = $scoreColumns;
        $this->requestStack = $requestStack;
        $this->deserializer = $deserializer;
        $this->stringUtils = $stringUtils;
        $this->gridFactory = $gridFactory;
        $this->columnFactory = $columnFactory;
        $this->authValidator = $authValidator;
        $this->columnsRendererFactory = $columnsRendererFactory;
        $this->config = $config;
    }

    /**
     * @return string
     * @Route("/report/general", name="report/general")
     */
    public function execute()
    {

        $decodedRules = [];
        $config = [
            'filters' => $this->config->getFilterConfig()->getFilters(),
            'rules' => $this->config->getParserConfig()->getDefaultRules(false)
        ];
        $gridHtml = '';
        $columns = [];
        $rules = $this->getRules();
        if ($rules) {
            $realDecodedRules = json_decode($rules, true);
            $decodedRules = $this->deserializer->deserialize($rules);
            unset($realDecodedRules['valid']);
            $parser = new DoctrineParser(
                $this->config->getParserConfig()->getClassName(),
                $this->config->getParserConfig()->getFieldsToProperties(),
                $this->config->getParserConfig()->getFieldPrefixesToClasses()
            );
            $parsed = $parser->parse($decodedRules);
            $columns = $this->getColumns();

            if (!$this->isPlainMode($columns)) {
                $firstOne = true;
                $index = 1;
                $aggregations = $this->getAggregations();
                foreach ($columns as $agg => $columnList) {
                    if (!isset($aggregations[$agg])) {
                        continue;
                    }
                    if (!$aggregations[$agg]['agg']) {
                        continue;
                    }
                    $func = $aggregations[$agg]['function'];
                    foreach ($columnList as $column) {
                        $replace = ($firstOne) ? 'SELECT '.$this->config->getParserConfig()->getAllObjects() : 'SELECT ';
                        $replaceWith = 'SELECT '.$func.'(object.' . $this->stringUtils->camelize($column) . ')';
                        $replaceWith .= ' as '.strtoupper($func).'_' . $this->stringUtils->camelize($column);
                        if (!$firstOne) {
                            $replaceWith .= ',';
                        }
                        $parsed = $parsed->copyWithReplacedString($replace, $replaceWith, false);
                        $firstOne = false;
                        $index++;
                    }
                }
                $groupByFields = $columns['p'];
                $selectString = $this->config->getParserConfig()->getSelectString($groupByFields);
                $groupByString = $this->config->getParserConfig()->getGroupByString($groupByFields);
                $parsed = $parsed->copyWithReplacedString('SELECT', 'SELECT '.$selectString.',', false);
                $parsed = $parsed->copyWithReplacedString('GROUP BY ', 'GROUP BY '.$groupByString, 'GROUP BY '.$groupByString);
            }
            $sql = $parsed->getQueryString();

//            echo $sql;exit;
            $config['rules'] = $realDecodedRules;
            $em = $this->managerRegistry->getManager();
            /** @var \Doctrine\ORM\Query $query */
            $query = $em->createQuery($sql);
            //add columns
            $query->setParameters($parsed->getParameters());
//            echo $query->getSQL();exit;
            $columns = $this->getColumns();
            $grid = $this->getGrid($columns);
//            echo "<pre>"; print_r($query->getArrayResult());exit;
            $grid->setRows($query->getArrayResult());
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
                'aggregations' => $this->getAggregations(),
                'fieldsHtml' => [
                    $this->getPlayerColumnsHtml($columns),
                    $this->getScoreColumnsHtml($columns)
                ]
            ]
        );
    }

    private function isPlainMode($columns)
    {
        foreach ($this->getAggregations() as $code => $settings) {
            if ($settings['agg']) {
                $requestKey = $settings['name'];
                if (isset($columns[$requestKey])) {
                    return false;
                }
            }
        }
        return true;
    }

    private function getPlayerColumnsHtml($selected)
    {
        $columns = $this->getSelectFields()[0];
        $columnsRenderer = $this->columnsRendererFactory->create(
            [$columns],
            $selected,
            [],
            [
                'values' => [
                    'label' => 'Values',
                    'function' => '',
                    'name' => Config\Aggregate::PLAYER_AGG_KEY
                ]
            ],
            '',
            'If you select aggregated values from the grid below, the selected fields in here will be added to the "Group By"'
        );
        return $columnsRenderer->render();
    }

    private function getScoreColumnsHtml($selected)
    {
        $allColumns = $this->getSelectFields();
        unset($allColumns[0]);
        $isPlainMode = $this->isPlainMode($selected);
        $columnsRenderer = $this->columnsRendererFactory->create(
            $allColumns,
            $selected,
            [
                0 => [
                    'label' => 'Values',
                    'fields' => ['values'],
                    'selected' => $isPlainMode
                ],
                1 => [
                    'label' => 'Aggregates',
                    'fields' => ['avg', 'min', 'max', 'sum'],
                    'selected' => !$isPlainMode
                ]
            ],
            $this->getAggregations()
        );
        return $columnsRenderer->render();
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
        $columns = $this->requestStack->getCurrentRequest()->get('c');
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
        return $this->config->getFieldsConfig()->getFields();
    }

    /**
     * @param $fields
     * @return \App\Grid
     */
    private function getGrid($fields)
    {
        $grid = $this->gridFactory->create([
            'title' => 'Report',
            'id' => 'custom-report-grid',
            'emptyMessage' => 'There are no scores matching your selection'
        ]);

        $selectFields = $this->getFlatSelectedFields();
        $isPlainMode = $this->isPlainMode($this->getColumns());
        $aggNames = array_keys($fields);
        if ($this->config->getAggregateConfig()->canAddCount($aggNames)) {
            $column = $this->columnFactory->create(['type' => 'integer', 'index' => 'count', 'label' => 'Count']);
            $grid->addColumn('count', $column);
        }
        foreach ($fields as $agg => $section) {
            $usedAgg = ($agg == Config\Aggregate::PLAYER_AGG_KEY) ? Config\Aggregate::VALUES_KEY: $agg;
            foreach ($section as $field) {
                if (isset($selectFields[$field])) {
                    $column = $this->columnFactory->create(
                        $this->config->getAggregateConfig()->transformColumnSettings($selectFields[$field], $usedAgg, $isPlainMode)
                    );
                    $grid->addColumn($usedAgg.$field, $column);
                }
            }
        }
        //exit;
        return $grid;
    }

    /**
     * @return array
     */
    private function getAggregations()
    {
        return $this->config->getAggregateConfig()->getAggregates();
    }
}
