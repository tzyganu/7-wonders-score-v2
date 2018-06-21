<?php
namespace App\Score\Calculator;

class Base implements CalculatorInterface
{
    /**
     * @param int $input
     * @return int
     */
    public function calculate($input)
    {
        return $input['score'];
    }
}
