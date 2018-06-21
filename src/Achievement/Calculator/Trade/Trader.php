<?php
namespace App\Achievement\Calculator\Trade;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Score;

class Trader implements CalculatorInterface
{
    const SCORE_LIMIT = 7;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $score->getTradeScore() >= static::SCORE_LIMIT;
    }

    /**
     * @param Player $player
     * @return null
     */
    public function getProgress(Player $player)
    {
        $max = 0;
        foreach ($player->getScores() as $score) {
            if ($score->getTradeScore() > $max) {
                $max = $score->getTradeScore();
            }
        }
        return new Progress($max, static::SCORE_LIMIT);
    }
}
