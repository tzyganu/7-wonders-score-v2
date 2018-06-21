<?php
namespace App\Tab;

use App\Tab;

class Container
{
    /**
     * @var Tab[]
     */
    private $tabs = [];
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $template = 'tab/container.html.twig';

    private $htmlId;

    /**
     * Container constructor.
     * @param \Twig_Environment $twig
     * @param string $template
     */
    public function __construct(
        \Twig_Environment $twig,
        $template = ''
    ) {
        $this->twig = $twig;
        if ($template) {
            $this->template = $template;
        }
    }

    /**
     * @param $key
     * @param Tab $tab
     */
    public function addTab($key, Tab $tab)
    {
        $this->tabs[$key] = $tab;
    }

    /**
     * @return Tab[]
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->twig->render($this->template, ['container' => $this]);
    }

    /**
     * @return string
     */
    public function getHtmlId()
    {
        if ($this->htmlId === null) {
            $this->htmlId = 'tab-'.md5(uniqid());
        }
        return $this->htmlId;
    }
}
