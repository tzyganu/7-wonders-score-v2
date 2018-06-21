<?php
namespace App\Achievement\Calculator\Science\Set;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Player;
use App\Entity\Score;

class Newton implements CalculatorInterface
{
    const SET_LIMIT = 2;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {

        return $this->getSetCount($score) >= static::SET_LIMIT;
    }

    /**
     * @param Score $score
     * @return int
     */
    protected function getSetCount(Score $score)
    {
        return min($score->getScienceGear(), $score->getScienceCompass(), $score->getScienceTablet());
    }

    /**
     * @param Player $player
     * @return Progress
     */
    public function getProgress(Player $player)
    {
        $max = 0;
        foreach ($player->getScores() as $score) {
            $setCount  = $this->getSetCount($score);
            if ($setCount > $max) {
                $max = $setCount;
            }
        }
        return new Progress($max, static::SET_LIMIT);
    }


}
