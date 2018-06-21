<?php
namespace App;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Button
{
    const DEFAULT_CLASS = 'btn btn-primary';
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $class;
    /**
     * @var string
     */
    private $label;
    /**
     * @var array
     */
    private $params;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlBuilder;

    /**
     * @param UrlGeneratorInterface $urlBuilder
     * @param array $options
     */
    public function __construct(
        UrlGeneratorInterface $urlBuilder,
        array $options = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->setClass(self::DEFAULT_CLASS);
        $fields = $this->getConstructorOptionsFields();
        foreach ($fields as $field) {
            if (isset($options[$field])) {
                $method = 'set'.ucfirst($field);
                $this->$method($options[$field]);
            }
        }
    }

    /**
     * @return array
     */
    protected function getConstructorOptionsFields()
    {
        return ['label', 'class', 'url', 'params'];

    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->urlBuilder->generate($this->url, $this->getParams());
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        if ($this->params === null) {
            $this->params = [];
        }
        return $this->params;
    }
}
