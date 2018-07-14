<?php
namespace App\Report;

class ColumnsRendererFactory
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $defaultTemplate;

    /**
     * ColumnsRendererFactory constructor.
     * @param \Twig_Environment $twig
     * @param string $defaultTemplate
     */
    public function __construct(\Twig_Environment $twig, $defaultTemplate)
    {
        $this->twig = $twig;
        $this->defaultTemplate = $defaultTemplate;
    }

    /**
     * @param array $fields
     * @param $selected
     * @param array $dependencyMap
     * @param array $aggregations
     * @param string $containerId
     * @param string $comment
     * @param $template
     * @return ColumnsRenderer
     */
    public function create(
        array $fields,
        $selected,
        $dependencyMap = [],
        $aggregations = [],
        $containerId = '',
        $comment = '',
        $template = null
    ) {
        if ($template === null) {
            $template = $this->defaultTemplate;
        }
        return new ColumnsRenderer($this->twig, $fields, $selected, $dependencyMap, $aggregations, $containerId, $comment, $template);
    }
}
