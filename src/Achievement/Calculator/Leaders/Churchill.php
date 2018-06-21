<?php
namespace App\Achievement\Calculator\Leaders;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Score;

class Churchill implements CalculatorInterface
{
    const SCORE_LIMIT = 10;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $score->getLeadersScore() >= static::SCORE_LIMIT;
    }

    /**
     * @param Player $player
     * @return null
     */
    public function getProgress(Player $player)
    {
        $max = 0;
        foreach ($player->getScores() as $score) {
            if ($score->getLeadersScore() > $max) {
                $max = $score->getLeadersScore();
            }
        }
        return new Progress($max, static::SCORE_LIMIT);
    }
}
