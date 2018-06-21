<?php
namespace App\Grid\Column;

use App\Grid\Column;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Factory
{
    /**
     * @var string
     */
    const DEFAULT_TYPE = 'text';
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var array
     */
    private $map = [
        'decimal'    => DecimalColumn::class,
        'icon'       => Icon::class,
        'integer'    => IntegerColumn::class,
        'percentage' => Percentage::class,
        'text'       => Text::class,
        'yesno'      => YesNo::class,
        'link'       => Link::class,
        'datetime'   => Datetime::class,
    ];

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param $data
     * @return Column
     * @throws \Exception
     */
    public function create($data)
    {
        $type = isset($data['type']) ? $data['type'] : self::DEFAULT_TYPE;
        unset($data['type']);
        if (!isset($this->map[$type])) {
            throw new \Exception("Unsupported column type {$type}");
        }
        $class = $this->map[$type];
        $data['urlGenerator'] = $this->urlGenerator;
        return new $class($data);
    }
}
