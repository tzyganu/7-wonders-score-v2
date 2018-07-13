<?php
namespace App\Report\Config;

use App\YamlLoader;

class Aggregate
{
    const PLAYER_AGG_KEY = 'p';
    const VALUES_KEY = 'c';
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $source;
    /**
     * @var array
     */
    private $aggregates;

    /**
     * Filter constructor.
     * @param YamlLoader $yamlLoader
     * @param $source
     */
    public function __construct(
        YamlLoader $yamlLoader,
        $source
    ) {
        $this->yamlLoader = $yamlLoader;
        $this->source = $source;
    }

    /**
     * @return array
     */
    public function getAggregates()
    {
        if ($this->aggregates === null) {
            $this->aggregates = $this->yamlLoader->load($this->source)['agg'];
        }
        return $this->aggregates;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getAggregateByName($name)
    {
        foreach ($this->getAggregates() as $aggregate) {
            if ($aggregate['name'] == $name) {
                return $aggregate;
            }
        }
        return null;
    }

    /**
     * @param array $names
     * @return bool
     */
    public function canAddCount(array $names) {
        foreach ($names as $name) {
            if ($agg = $this->getAggregateByName($name)) {
                if ($agg['count']) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $settings
     * @param $aggName
     * @param $isPlaneMode
     * @return mixed
     */
    public function transformColumnSettings($settings, $aggName, $isPlaneMode)
    {
        $agg = $this->getAggregateByName($aggName);
        if (!$agg) {
            return $settings;
        }
        if (!$isPlaneMode) {
            foreach ($this->getColumnFieldsToTransform() as $fieldName) {
                if (isset($settings[$fieldName])) {
                    $settings[$fieldName] = $this->transformFieldName($settings[$fieldName], $agg);
                }
            }
        }
        //replace the label
        if ($agg['agg'] && isset($settings['label'])) {
            $settings['label'] .= '(' . $agg['name'] . ')';
        }
        if ($agg['columnType']) {
            $settings['type'] = $agg['columnType'];
        }
        return $settings;
    }

    /**
     * @param $field
     * @param $agg
     * @return array|mixed|string
     */
    protected function transformFieldName($field, $agg)
    {
        if (is_array($field)) {
            foreach ($field as $key => $value) {
                $field[$key] = $this->transformFieldName($value, $agg);
            }
            return $field;
        } elseif (is_string($field)) {
            $field = str_replace('.', '_', $field);
            if ($agg['agg']) {
                return strtoupper($agg['name']).'_'.$field;
            }
        }
        return $field;
    }

    protected function getColumnFieldsToTransform()
    {
        return ['key', 'labelKey', 'index', 'params'];
    }
}
