<?php
namespace  App\Achievement;

use App\Achievement\Calculator\CalculatorInterface;

class Pool
{
    /**
     * @var CalculatorInterface[]
     */
    private $calculators = [];

    /**
     * Pool constructor.
     * @param iterable $calculators
     */
    public function __construct(iterable $calculators)
    {
        foreach ($calculators->getIterator() as $item) {
            $this->calculators[$this->calculateAlias(get_class($item))] = $item;
        }
    }

    /**
     * @param $className
     * @return string
     */
    private function calculateAlias($className)
    {
        $parts = explode('\\', $className);
        $part = end($parts);
        return strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $part), '_'));
    }

    /**
     * @param $alias
     * @return CalculatorInterface
     * @throws \Exception
     */
    public function getCalculator($alias)
    {
        if (array_key_exists($alias, $this->calculators)) {
            return $this->calculators[$alias];
        }
        throw new \Exception("Achievement identifier {$alias} is not valid");
    }
}
