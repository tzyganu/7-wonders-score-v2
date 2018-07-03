<?php
namespace App\Achievement\Calculator\Military\Shield;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Player;
use App\Entity\Score;

class Hedgehog implements CalculatorInterface
{
    const SHIELD_LIMIT = 100;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $this->getShieldCount($score->getPlayer()) > static::SHIELD_LIMIT;
    }

    /**
     * @param Player $player
     * @return int
     */
    protected function getShieldCount(Player $player)
    {
        $count = 0;
        foreach ($player->getScores() as $score) {
            $count += (int)$score->getMilitaryShield();
        }
        return $count;
    }

    /**
     * @param Player $player
     * @return Progress
     */
    public function getProgress(Player $player)
    {
        return new Progress($this->getShieldCount($player), static::SHIELD_LIMIT);
    }

}
