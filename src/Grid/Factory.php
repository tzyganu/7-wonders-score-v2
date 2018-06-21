<?php
namespace App\Grid;

use App\Grid;

class Factory
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
     * @param array $data
     * @return Grid
     */
    public function create(array $data = [])
    {
        return new Grid($this->twig, $data);
    }
}
