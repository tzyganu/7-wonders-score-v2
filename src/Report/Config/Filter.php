<?php
namespace App\Report\Config;

use App\Score\Columns;
use App\Util\StringUtils;
use App\YamlLoader;
use Doctrine\Common\Persistence\ManagerRegistry;

class Filter
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
     * @var array
     */
    private $filters;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * Filter constructor.
     * @param YamlLoader $yamlLoader
     * @param Columns $scoreColumns
     * @param StringUtils $stringUtils
     * @param $source
     */
    public function __construct(
        YamlLoader $yamlLoader,
        Columns $scoreColumns,
        StringUtils $stringUtils,
        ManagerRegistry $managerRegistry,
        $source
    ) {
        $this->yamlLoader = $yamlLoader;
        $this->scoreColumns = $scoreColumns;
        $this->stringUtils = $stringUtils;
        $this->managerRegistry = $managerRegistry;
        $this->source = $source;
    }

    /**
     * @return array|null
     */
    public function getFilters()
    {
        if ($this->filters === null) {
            $this->loadConfig();
        }
        return $this->filters;
    }

    /**
     * @throws \Exception
     */
    private function loadConfig()
    {
        $config = $this->yamlLoader->load($this->source);
        if (!isset($config['filters'])) {
            throw new \Exception("Report is not properly configured. Filters are missing");
        }
        $this->filters = $config['filters'];
        foreach ($this->filters as $key => $values) {
            if (isset($values['values']['fixed'])) {
                $this->filters[$key]['values'] = $values['values']['fixed'];
                unset($this->filters[$key]['values']['fixed']);
            }
            elseif (isset($values['values']['entity'])) {
                $filterValues = $this->getEntityFilterValues($values['values']);
                unset($this->filters[$key]['values']);
                $this->filters[$key]['values'] = $filterValues;
            }
        }
        foreach ($this->scoreColumns->getColumns() as $key => $column) {
            $this->filters[] = [
                'id' =>  $key,
                'label' => $column['long_label'],
                'name' =>  'score.'.$key,
                'type' => 'integer',
                'input' =>  'text',
                'operators' => ['equal','not_equal', 'in', 'not_in', 'less', 'less_or_equal', 'greater',
                    'greater_or_equal', 'between', 'not_between', 'is_null', 'is_not_null']
            ];
        }
    }

    /**
     * @param $config
     * @return array
     */
    private function getEntityFilterValues($config)
    {
        $entity = $config['entity'];
        $valueKey = $config['value'];
        $labelKey = $config['label'];
        $cacheKey = $this->getHashKey([$entity, $valueKey, $labelKey]);
        if (!isset($this->cache[$cacheKey])) {
            $entities = $this->managerRegistry->getRepository($entity)->findAll();
            $this->cache[$cacheKey] = [];
            foreach ($entities as $entity) {
                $this->cache[$cacheKey][$entity->$valueKey()] = $entity->$labelKey();
            }
        }
        return $this->cache[$cacheKey];
    }

    /**
     * @param array $arr
     * @return string
     */
    private function getHashKey(array $arr)
    {
        return md5(implode('##', $arr));
    }

}
