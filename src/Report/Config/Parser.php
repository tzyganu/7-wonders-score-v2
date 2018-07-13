<?php
namespace App\Report\Config;

use App\Score\Columns;
use App\Util\StringUtils;
use App\YamlLoader;
use FL\QBJSParser\Parser\Doctrine\SelectPartialParser;

class Parser
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
     * @var string
     */
    private $className;
    /**
     * @var array
     */
    private $fieldsToProperties;
    /**
     * @var array
     */
    private $fieldPrefixesToClasses;

    /**
     * Parser constructor.
     * @param YamlLoader $yamlLoader
     * @param Columns $scoreColumns
     * @param StringUtils $stringUtils
     * @param $source
     */
    public function __construct(
        YamlLoader $yamlLoader,
        Columns $scoreColumns,
        StringUtils $stringUtils,
        $source
    ) {
        $this->yamlLoader = $yamlLoader;
        $this->scoreColumns = $scoreColumns;
        $this->stringUtils = $stringUtils;
        $this->source = $source;
    }

    /**
     * @return array|null
     */
    public function getFieldsToProperties()
    {
        if ($this->fieldsToProperties === null) {
            $this->loadConfig();
        }
        return $this->fieldsToProperties;
    }

    /**
     * @return null|string
     */
    public function getClassName()
    {
        if ($this->className === null) {
            $this->loadConfig();
        }
        return $this->className;
    }

    /**
     * @return array|null
     */
    public function getFieldPrefixesToClasses()
    {
        if ($this->fieldPrefixesToClasses === null) {
            $this->loadConfig();
        }
        return $this->fieldPrefixesToClasses;
    }

    /**
     * @throws \Exception
     */
    private function loadConfig()
    {
        $config = $this->yamlLoader->load($this->source);
        if (!isset($config['className'])) {
            throw new \Exception("Report is not properly configured. Class Name is missing");
        }
        $this->className = $config['className'];

        $this->fieldsToProperties = isset($config['fieldsToProperties'])
            ? array_combine($config['fieldsToProperties'], $config['fieldsToProperties'])
            : [];
        foreach ($this->scoreColumns->getColumns() as $key => $column) {
            $this->fieldsToProperties[$key] = $this->stringUtils->camelize($key);
        }
        $this->fieldPrefixesToClasses = $config['fieldPrefixesToClasses'] ?? [];
    }

    /**
     * @return array
     */
    public function getObjectKeys()
    {
        return array_keys($this->getFieldPrefixesToClasses());
    }

    /**
     * @param $fields
     * @param bool $asString
     * @return array|string
     */
    public function getGroupByString($fields, $asString = true)
    {
        $groupBy = [];
        foreach ($fields as $field) {
            $groupBy[] = SelectPartialParser::OBJECT_WORD.'.'.$field;
        }
        return ($asString) ? implode(', ', $groupBy) : $groupBy;
    }

    /**
     * @param bool $asString
     * @return array|string
     */
    public function getAllObjects($asString = true)
    {
        $objects = [SelectPartialParser::OBJECT_WORD];
        foreach ($this->getObjectKeys() as $key) {
            $objects[] = SelectPartialParser::OBJECT_WORD.'_'.$key;
        }
        return ($asString) ? implode(', ', $objects) : $objects;
    }

    /**
     * @param $fields
     * @param bool $withCount
     * @param bool $asString
     * @return array|string
     */
    public function getSelectString($fields, $withCount = true, $asString = true)
    {
        $select = [];
        if ($withCount) {
            $select[] = 'COUNT('.SelectPartialParser::OBJECT_WORD.') as count';
        }
        $objectFields = $this->getObjectKeys();
        foreach ($fields as $field) {
            if (in_array($field, $objectFields)) {
                $select[] = SelectPartialParser::OBJECT_WORD.'_'.$field.'.id as '.$field.'_id';
                $select[] = SelectPartialParser::OBJECT_WORD.'_'.$field.'.name as '.$field.'_name';
            } else {
                $select[] = 'object.'.$field;
            }
        }
        return ($asString) ? implode(', ', $select) : $select;
    }

    /**
     * @param bool $asJson
     * @return array|string
     */
    public function getDefaultRules($asJson = true)
    {
        $rules = [
            'condition' => 'AND',
            'rules' => [
                '0' => [
                    'id' => 'game.id',
                    'field' => 'game.id',
                    'type' => 'integer',
                    'input' => 'text',
                    'operator' => 'greater_or_equal',
                    'value' => 1
                ]
            ],
            'valid' => true
        ];

        return ($asJson) ? json_encode($rules) : $rules;
    }
}
