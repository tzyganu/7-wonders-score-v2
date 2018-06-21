<?php
namespace App\Grid\Column;

use App\Grid\Column;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Link extends Column
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var array
     */
    private $params;
    /**
     * @var array
     */
    private $urlParams;

    /**
     * @var string
     */
    private $labelKey;

    /**
     * @return array
     */
    protected function getConstructorOptionsFields()
    {
        $fields = parent::getConstructorOptionsFields();
        $fields[] = 'url';
        $fields[] = 'params';
        $fields[] = 'urlGenerator';
        $fields[] = 'urlParams';
        $fields[] = 'labelKey';
        return $fields;
    }

    /**
     * @param $value
     * @return string
     */
    public function formatLinkValue($value, $row)
    {
        return '<a href="'.$value.'">'.$this->getRowLabel($row).'</a>';
    }

    /**
     * @param $row
     * @param null $default
     * @return string
     * @throws \Exception
     */
    public function render($row, $default = null)
    {
        $getParams = [];
        foreach ($this->getParams() as $name => $index) {
            $getParams[$name] = $this->getRowValue($row, $index, null);
//            if (is_array($row)) {
//                if (isset($row[$index])) {
//                    $getParams[$name] = $row[$index];
//                }
//            } elseif (is_object($row)) {
//                $method = $index;
//                if (method_exists($row, $method)) {
//                    $getParams[$name] = $row->$method();
//                }
//            } else {
//                throw new \Exception("Row must be object or array");
//            }
        }
        $getParams = array_merge($getParams, $this->getUrlParams());
        $url = $this->getUrlGenerator()->generate($this->getUrl(), $getParams);
        return $this->formatLinkValue($url, $row);
    }

    protected function formatValue($value)
    {
        return $value;
    }


    /**
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     */
    public function setUrlGenerator($urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    public function getUrlGenerator()
    {
        return $this->urlGenerator;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getUrl()
    {
        if (!$this->url) {
            throw new \Exception("Url not set for link column");
        }
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @param array $urlParams
     */
    public function setUrlParams($urlParams)
    {
        $this->urlParams = $urlParams;
    }

    /**
     * @return array
     */
    public function getUrlParams()
    {
        return ($this->urlParams !== null) ? $this->urlParams : [];
    }

    /**
     * @return string
     */
    public function getLabelKey()
    {
        return $this->labelKey;
    }

    public function getRowLabel($row)
    {
        $labelKey = $this->getLabelKey();
        $label = '';
        if ($labelKey) {
            $label = $this->getRowValue($row, $labelKey, null);
        }
        if ($label === null || $label === '') {
            $label = $this->getLabel();
        }
        return $label;
    }

    /**
     * @param string $labelKey
     */
    public function setLabelKey($labelKey)
    {
        $this->labelKey = $labelKey;
    }
}
