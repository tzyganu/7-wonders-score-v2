<?php
namespace App\Grid;

use App\YamlLoader;
use App\Grid\Column\Factory as ColumnFactory;
use App\Button\Factory as ButtonFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Loader
{
    /**
     * @var Factory
     */
    private $gridFactory;
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $fileLocation;
    /**
     * @var ColumnFactory
     */
    private $columnFactory;
    /**
     * @var ButtonFactory
     */
    private $buttonFactory;

    /**
     * @var ContainerInterface
     */
    private $conainer;

    /**
     * Loader constructor.
     * @param Factory $gridFactory
     * @param ColumnFactory $columnFactory
     * @param ButtonFactory $buttonFactory
     * @param YamlLoader $yamlLoader
     * @param string $fileLocation
     */
    public function __construct(
        Factory $gridFactory,
        ColumnFactory $columnFactory,
        ButtonFactory $buttonFactory,
        ContainerInterface $container,
        YamlLoader $yamlLoader,
        $fileLocation = '../config/grid/'
    ) {
        $this->gridFactory   = $gridFactory;
        $this->columnFactory = $columnFactory;
        $this->buttonFactory = $buttonFactory;
        $this->yamlLoader    = $yamlLoader;
        $this->conainer      = $container;
        $this->fileLocation  = $fileLocation;
    }

    /**
     * @param $name
     * @return \App\Grid
     */
    public function loadGrid($name)
    {
        $config = $this->yamlLoader->load($this->locateGridConfig($name));
        return $this->buildGrid($config);
    }

    /**
     * @param $name
     * @return string
     */
    private function locateGridConfig($name)
    {
        return $this->fileLocation.$name.'.yml';
    }

    /**
     * @param $config
     * @return \App\Grid
     */
    private function buildGrid($config)
    {
        $options = isset($config['options']) ? $config['options'] : [];
        $grid = $this->gridFactory->create($options);
        if (isset($config['columns'])) {
            if (isset($config['columns']['_class'])) {
                /** @var ProviderInterface $instance */
                $instance = $this->conainer->get($config['columns']['_class']);
                foreach ($instance->getColumns() as $key => $column) {
                    $grid->addColumn($key, $column);
                }
            } else {
                foreach ($config['columns'] as $columnKey => $columnData) {
                    $grid->addColumn($columnKey, $this->columnFactory->create($columnData));
                }
            }
        }
        if (isset($config['buttons'])) {
            foreach ($config['buttons'] as $id => $buttonData) {
                $grid->addButton($id, $this->buttonFactory->create($buttonData));
            }
        }
        return $grid;
    }
}
