<?php
namespace App\Achievement\Calculator\Cities;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Player;
use App\Entity\Score;

class Troy implements CalculatorInterface
{
    const SCORE_LIMIT = 15;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $score->getCitiesScore() >= static::SCORE_LIMIT;
    }

    /**
     * @param Player $player
     * @return null
     */
    public function getProgress(Player $player)
    {
        $max = 0;
        foreach ($player->getScores() as $score) {
            if ($score->getCitiesScore() > $max) {
                $max = $score->getCitiesScore();
            }
        }
        return new Progress($max, static::SCORE_LIMIT);
    }
}
