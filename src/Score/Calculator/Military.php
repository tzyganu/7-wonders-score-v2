<?php
namespace App\Score\Calculator;

class Military implements CalculatorInterface
{
    const FIVE = 'five';
    const THREE = 'three';
    const ONE = 'one';
    const MINUS_ONE = 'minus_one';

    /**
     * @param $input
     * @return int
     */
    public function calculate($input)
    {
        $score = 0;
        foreach ($this->getParts() as $code => $multiplier) {
            $score += $this->getCount($code, $input) * $multiplier;
        }
        return $score;
    }

    /**
     * @param $part
     * @param $input
     * @return int
     */
    private function getCount($part, $input)
    {
        return isset($input[$part]) && (int)($input[$part]) > 0 ? (int)($input[$part]) : 0;
    }

    /**
     * @return array
     */
    private function getParts()
    {
        return [
            self::FIVE => 5,
            self::THREE => 3,
            self::ONE => 1,
            self::MINUS_ONE => -1,
        ];
    }
}