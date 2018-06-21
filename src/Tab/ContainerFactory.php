<?php
namespace App\Tab;

class ContainerFactory
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return Container
     */
    public function create()
    {
        return new Container($this->twig);
    }
}
