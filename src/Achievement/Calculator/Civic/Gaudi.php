<?php
namespace App\Achievement\Calculator\Civic;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Score;

class Gaudi implements CalculatorInterface
{
    const LIMIT = 25;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $score->getCivicScore() >= static::LIMIT;
    }

    /**
     * @param Player $player
     * @return null
     */
    public function getProgress(Player $player)
    {
        $max = 0;
        foreach ($player->getScores() as $score) {
            if ($score->getCivicScore() > $max) {
                $max = $score->getCivicScore();
            }
        }
        return new Progress($max, static::LIMIT);
    }
}
