<?php
namespace App\Grid\Provider;

use App\Entity\Category;
use App\Grid\Column;
use App\Grid\ProviderInterface;
use App\Score\Columns;
use App\Util\StringUtils;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Yaml\Yaml;

class Game implements ProviderInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var Column\Factory
     */
    private $columnFactory;
    /**
     * @var Columns
     */
    private $scoreColumns;
    /**
     * @var StringUtils
     */
    private $stringUtils;

    /**
     * Game constructor.
     * @param ManagerRegistry $managerRegistry
     * @param Column\Factory $columnFactory
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Column\Factory $columnFactory,
        Columns $scoreColumns,
        StringUtils $stringUtils
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->columnFactory = $columnFactory;
        $this->scoreColumns = $scoreColumns;
        $this->stringUtils = $stringUtils;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        /** @var Category[] $categories */
        $categories = $this->managerRegistry->getRepository(Category::class)->findBy([], ['sortOrder' => 'ASC']);
        $columns = [];
        $columns['game'] = $this->columnFactory->create([
            'type' => 'link',
            'label' => 'Game',
            'labelKey' => 'getGame.getId',
            'url' => 'game/view',
            'params' => [
                'id' => 'getGame.getId'
            ]
        ]);
        $columns['player'] = $this->columnFactory->create([
            'type' => 'link',
            'label' => 'Player',
            'labelKey' => 'getPlayer.getName',
            'url' => 'player/view',
            'params' => [
                'id' => 'getPlayer.getId'
            ]
        ]);
        $columns['wonder'] = $this->columnFactory->create([
            'type' => 'link',
            'label' => 'Wonder',
            'labelKey' => 'getWonder.getName',
            'url' => 'wonder/view',
            'params' => [
                'id' => 'getWonder.getId'
            ]
        ]);
        $columns['side'] = $this->columnFactory->create([
            'type' => 'text',
            'label' => 'Side',
            'index' => 'getSide'
        ]);
        foreach ($categories as $category) {
            foreach ($this->scoreColumns->getCategoryColumns($category->getCode()) as $key => $col) {
                $columns[$key] = $this->columnFactory->create([
                    'type' => 'integer',
                    'label' => $col['grid_label'],
                    'iconClass' => $category->getIconClass(),
                    'index' => $this->stringUtils->camelize('get_'.$key)
                ]);
            }
        }
        $columns['total_score'] = $this->columnFactory->create([
            'type' => 'integer',
            'label' => 'Total Score',
            'index' => 'getTotalScore',
            'defaultSort' => true,
            'defaultSortDir' =>  'ASC'
        ]);
        $columns['rank'] = $this->columnFactory->create([
            'type' => 'integer',
            'label' => 'Rank',
            'index' => 'getRank',
            'defaultSort' => true,
            'defaultSortDir' =>  'ASC'
        ]);
        return $columns;

    }
}
