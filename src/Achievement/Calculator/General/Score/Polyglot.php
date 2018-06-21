<?php
namespace App\Achievement\Calculator\General\Score;

use App\Achievement\Calculator\CalculatorInterface;
use App\Entity\Player;

class Polyglot extends Specialist implements CalculatorInterface
{
    const ZERO_SCORE_LIMIT = 0;

    /**
     * @param $zeroScores
     * @return bool
     */
    protected function compareZeroScores($zeroScores)
    {
        return $zeroScores <= static::ZERO_SCORE_LIMIT;
    }

    /**
     * @param Player $player
     * @return null
     */
    public function getProgress(Player $player)
    {
        return null;
    }


}
