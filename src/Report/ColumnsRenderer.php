<?php
namespace App\Report;

class ColumnsRenderer
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var array
     */
    private $fields;
    /**
     * @var array
     */
    private $selected;
    /**
     * @var array
     */
    private $aggregations;
    /**
     * @var string
     */
    private $template;
    /**
     * @var string
     */
    private $containerId;
    /**
     * @var string
     */
    private $comment;
    /**
     * @var array
     */
    private $dependencyMap;

    /**
     * ColumnsRenderer constructor.
     * @param \Twig_Environment $twig
     * @param array $fields
     * @param $selected
     * @param array $aggregations
     * @param string $containerId
     * @param string $comment
     * @param $template
     */
    public function __construct(
        \Twig_Environment $twig,
        array $fields,
        $selected,
        $dependencyMap = [],
        $aggregations = [],
        $containerId = '',
        $comment = '',
        $template
    ) {
        $this->twig = $twig;
        $this->fields = $fields;
        $this->selected = $selected;
        $this->dependencyMap = $dependencyMap;
        $this->aggregations = $aggregations;
        $this->containerId = $containerId;
        $this->comment = $comment;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->twig->render(
            $this->template,
            [
                'object' => $this,
            ]
        );
    }

    /**
     * @return string
     */
    public function getContainerId()
    {
        if (empty($this->containerId)) {
            $this->containerId = md5(uniqid('filter-columns'));
        }
        return $this->containerId;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * @return array
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @return array
     */
    public function getDependencyMap()
    {
        return $this->dependencyMap;
    }

    /**
     * @return string
     */
    public function getUiConfig()
    {
        $config = [];
        foreach ($this->getFields() as $group) {
            $config[] = $group['code'];
        }
        return json_encode(['config' => $config, 'dependencyMap' => $this->getDependencyMap()]);
    }

    /**
     * @param $field
     * @param $agg
     * @return bool
     */
    public function isSelected($field, $agg)
    {
        return isset($this->selected[$agg]) && in_array($field, $this->selected[$agg]);
    }
}
