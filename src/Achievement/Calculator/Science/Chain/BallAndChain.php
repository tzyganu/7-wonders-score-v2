<?php
namespace App\Achievement\Calculator\Science\Chain;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Player;
use App\Entity\Score;

class BallAndChain implements CalculatorInterface
{
    const CHAIN_LiMIT = 4;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $this->getChainCount($score) >= static::CHAIN_LiMIT;
    }

    /**
     * @param Player $player
     * @return Progress
     */
    public function getProgress(Player $player)
    {
        $max = 0;
        foreach ($player->getScores() as $score) {
            $setCount  = $this->getChainCount($score);
            if ($setCount > $max) {
                $max = $setCount;
            }
        }
        return new Progress($max, static::CHAIN_LiMIT);
    }

    /**
     * @param Score $score
     * @return int
     */
    protected function getChainCount(Score $score)
    {
        return max($score->getScienceGear(), $score->getScienceCompass(), $score->getScienceTablet());
    }

}
