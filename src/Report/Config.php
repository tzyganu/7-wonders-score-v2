<?php
namespace App\Report;

use App\Report\Config\Aggregate;
use App\Report\Config\Fields;
use App\Report\Config\Filter;
use App\Report\Config\Parser;

class Config
{
    /**
     * @var Parser
     */
    private $parserConfig;
    /**
     * @var Filter
     */
    private $filterConfig;
    /**
     * @var Fields
     */
    private $fieldsConfig;
    /**
     * @var Aggregate
     */
    private $aggregateConfig;

    /**
     * Config constructor.
     * @param Parser $parserConfig
     * @param Filter $filterConfig
     * @param Fields $fieldsConfig
     * @param Aggregate $aggregateConfig
     */
    public function __construct(Parser $parserConfig, Filter $filterConfig, Fields $fieldsConfig, Aggregate $aggregateConfig)
    {
        $this->parserConfig = $parserConfig;
        $this->filterConfig = $filterConfig;
        $this->fieldsConfig = $fieldsConfig;
        $this->aggregateConfig = $aggregateConfig;
    }

    /**
     * @return Aggregate
     */
    public function getAggregateConfig()
    {
        return $this->aggregateConfig;
    }

    /**
     * @return Parser
     */
    public function getParserConfig()
    {
        return $this->parserConfig;
    }

    /**
     * @return Filter
     */
    public function getFilterConfig()
    {
        return $this->filterConfig;
    }

    /**
     * @return Fields
     */
    public function getFieldsConfig()
    {
        return $this->fieldsConfig;
    }
}
