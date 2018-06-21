<?php
namespace App\Score\Calculator;

class Cash implements CalculatorInterface
{
    const COINS = 'coins';
    const MINUS_ONE = 'minus_one';

    /**
     * @param $input
     * @return int
     */
    public function calculate($input)
    {
        $score = 0;
        $score += (int)($this->getCount(self::COINS, $input) / 3);
        $score -= $this->getCount(self::MINUS_ONE, $input);
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
}
