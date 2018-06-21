<?php
namespace App\Score;

use App\Score\Calculator\CalculatorInterface;

class CalculatorPool
{
    /**
     * @var CalculatorInterface[]
     */
    private $calculators;

    /**
     * @param $class
     * @return CalculatorInterface
     * @throws \Exception
     */
    public function getCalculator($class)
    {
        $map = $this->getCalculatorMap();
        if(isset($map[$class])) {
            return $map[$class];
        }
        return $map['_default'];
    }

    /**
     * @return Calculator\CalculatorInterface[]|null
     */
    private function getCalculatorMap()
    {
        if ($this->calculators === null) {
            $classes = [
                '_default' => Calculator\Base::class,
                'cash' => Calculator\Cash::class,
                'military' => Calculator\Military::class,
                'science' => Calculator\Science::class,
            ];
            foreach ($classes as $key => $class) {
                $this->calculators[$key] = new $class();
            }
        }
        return $this->calculators;
    }
}
