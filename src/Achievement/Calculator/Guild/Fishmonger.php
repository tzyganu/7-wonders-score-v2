<?php
namespace App\Achievement\Calculator\Guild;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Score;

class Fishmonger implements CalculatorInterface
{
    const SCORE_LIMIT = 20;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $score->getGuildScore() >= static::SCORE_LIMIT;
    }

    /**
     * @param Player $player
     * @return null
     */
    public function getProgress(Player $player)
    {
        $max = 0;
        foreach ($player->getScores() as $score) {
            if ($score->getGuildScore() > $max) {
                $max = $score->getGuildScore();
            }
        }
        return new Progress($max, static::SCORE_LIMIT);
    }
}
