<?php
namespace App\Score\Calculator;

class Science implements CalculatorInterface
{
    const TABLET = 'tablet';
    const GEAR = 'gear';
    const COMPASS = 'compass';

    /**
     * @param $input
     * @return int
     */
    public function calculate($input)
    {
        $filtered = $this->filterInput($input);
        $score = min($filtered) * 7;
        foreach ($filtered as $count) {
            $score += $count * $count;
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
     * @param $input
     * @return array
     */
    private function filterInput($input)
    {
        return [
            self::TABLET => $this->getCount(self::TABLET, $input),
            self::GEAR => $this->getCount(self::GEAR, $input),
            self::COMPASS => $this->getCount(self::COMPASS, $input),
        ];
    }
}
