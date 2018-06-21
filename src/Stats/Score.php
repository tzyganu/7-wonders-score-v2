<?php
namespace App\Stats;

use App\Entity\Category;
use App\Score\Columns;
use App\Util\StringUtils;
use Doctrine\Common\Persistence\ManagerRegistry;

class Score
{
    const ALIAS = 'score';
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var Columns
     */
    private $scoreColumns;
    /**
     * @var Category[]
     */
    private $categories;
    /**
     * @var StringUtils
     */
    private $stringUtils;

    /**
     * Score constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Columns $scoreColumns,
        StringUtils $stringUtils
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->scoreColumns = $scoreColumns;
        $this->stringUtils = $stringUtils;
    }

    /**
     * @param $function
     * @param bool $joinPlayers
     * @return array
     */
    public function getStats($function, $joinPlayers = false)
    {
        $scoreRepo = $this->managerRegistry->getRepository(\App\Entity\Score::class);
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $stats = [];
        $sections = ['total'];
        foreach ($this->getCategories() as $category) {
            $sections[] = $category->getCode();
        }
        foreach ($sections as $section) {
            $queryBuilder = $scoreRepo->createQueryBuilder(self::ALIAS);
            $queryBuilder->select($this->getSectionSelectStatement($section, $function));
            $stats[$section] = $this->prepareResults($queryBuilder->getQuery()->getResult(), $joinPlayers);
        }
        return $stats;

    }

    /**
     * @param $results
     * @param $joinPlayers
     * @return array
     */
    private function prepareResults($results, $joinPlayers)
    {
        if (!isset($results[0])) {
            return [];
        }
        $results = $results[0];
        $processed = [];
        foreach ($this->scoreColumns->getColumns() as $column => $settings) {
            if (!isset($results[$column])) {
                continue;
            }
            $item = [];
            $item['value'] = $results[$column];
            $item['label'] = $settings['long_label'];
            $item['players'] = [];
            if ($joinPlayers) {
                $players = [];
                $repo = $this->managerRegistry->getRepository(\App\Entity\Score::class);
                /** @var \App\Entity\Score[] $scores */
                $scores = $repo->findBy([$this->stringUtils->camelize($column) => $results[$column]]);
                foreach ($scores as $score) {
                    $playerId = $score->getPlayer()->getId();
                    if (!isset($item['players'][$playerId])) {
                        $item['players'][$playerId] = [
                            'id' => $score->getPlayer()->getId(),
                            'name' => $score->getPlayer()->getName(),
                            'game' => $score->getGame()->getId()
                        ];
                    }
                }
            }
            $processed[$column] = $item;
        }
        return $processed;
    }


    private function getSectionSelectStatement($section, $function)
    {
        $statements = [];
        foreach ($this->scoreColumns->getCategoryColumns($section) as $column => $settings){
            $statements[] = $this->buildSelectStatement($function, $column);
        }

        return implode(', ', $statements);
    }
    /**
     * @param $function
     * @param $field
     * @return string
     */
    private function buildSelectStatement($function, $field)
    {
        $camelized = $this->stringUtils->camelize($field);
        return strtoupper($function).'('.self::ALIAS.'.'.$camelized.') AS '.$field;
    }

    /**
     * @return Category[]
     */
    private function getCategories()
    {
        if ($this->categories === null) {
            $this->categories = $this->managerRegistry->getRepository(Category::class)->findAll();
        }
        return $this->categories;
    }

}
