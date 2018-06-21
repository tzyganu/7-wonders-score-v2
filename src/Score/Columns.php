<?php
namespace App\Score;

use App\YamlLoader;

class Columns
{
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $configFile;
    /**
     * @var array
     */
    private $columns;

    /**
     * Columns constructor.
     * @param YamlLoader $yamlLoader
     * @param string $configFile
     */
    public function __construct(
        YamlLoader $yamlLoader,
        $configFile
    ) {
        $this->yamlLoader = $yamlLoader;
        $this->configFile = $configFile;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        if ($this->columns === null) {
            $this->columns = $this->yamlLoader->load($this->configFile);
        }
        return $this->columns;
    }

    /**
     * @param $category
     * @return array
     */
    public function getCategoryColumns($category)
    {
        return array_filter($this->getColumns(), function($item) use ($category) {
            return ($item['category'] == $category);
        });
    }
}
