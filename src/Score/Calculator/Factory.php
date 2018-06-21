<?php
namespace App\Score\Calculator;

class Factory
{
    /**
     * @param $className
     * @return CalculatorInterface
     * @throws \Exception
     */
    public function create($className)
    {
        if ($className instanceof CalculatorInterface) {
            return new $className();
        }
        throw new \Exception("Class {$className} does not implement ".CalculatorInterface::class);
    }
}
