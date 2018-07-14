<?php
namespace App\Report\Config;

use App\Entity\Category;
use App\Score\Columns;
use App\Util\StringUtils;
use App\YamlLoader;
use Doctrine\Common\Persistence\ManagerRegistry;

class Fields
{
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $source;

    /**
     * @var Columns
     */
    private $scoreColumns;
    /**
     * @var StringUtils
     */
    private $stringUtils;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var array
     */
    private $fields;

    /**
     * Fields constructor.
     * @param YamlLoader $yamlLoader
     * @param string $source
     * @param Columns $scoreColumns
     * @param StringUtils $stringUtils
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        YamlLoader $yamlLoader,
        $source,
        Columns $scoreColumns,
        StringUtils $stringUtils,
        ManagerRegistry $managerRegistry
    ) {
        $this->yamlLoader = $yamlLoader;
        $this->source = $source;
        $this->scoreColumns = $scoreColumns;
        $this->stringUtils = $stringUtils;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @return array|mixed
     */
    public function getFields()
    {
        if ($this->fields === null) {
            $this->fields = $this->yamlLoader->load($this->source)['fields'];
            $categories = $this->getCategoriesByKey();
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
                        'index' => $this->stringUtils->camelize($key),
                        'group' => false,
                        'agg' => true,
                    ];
                }
                $group['fields'] = $fields;
                $this->fields[] = $group;
            }
            $this->fields[] = [
                'label' => 'Total Score',
                'code' => 'total',
                'fields' => [
                    'total_score' => [
                        'id' => 'total_score',
                        'long_label' => 'Total score',
                        'label' => '+',
                        'type' => 'integer',
                        'iconClass' => '',
                        'index' => "totalScore",
                        'group' => false,
                        'agg' => true,
                    ]
                ]
            ];
        }
        return $this->fields;
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
