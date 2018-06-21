<?php
namespace App\Achievement\Calculator\Cash;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Score;

class Trump implements CalculatorInterface
{
    const LIMIT = 20;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $score->getCashCoins() >= static::LIMIT;
    }

    /**
     * @param Player $player
     * @return null
     */
    public function getProgress(Player $player)
    {
        $max = 0;
        foreach ($player->getScores() as $score) {
            if ($score->getCashCoins() > $max) {
                $max = $score->getCashCoins();
            }
        }
        return new Progress($max, static::LIMIT);
    }
}
