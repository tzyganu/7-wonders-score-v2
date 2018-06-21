<?php
namespace App\Achievement\Calculator\Military;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Score;

class Soldier implements CalculatorInterface
{
    const MILITARY_LIMIT = 18;
    const GAME_LIMIT = 1;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $this->getMilitaryLimitCount($score->getPlayer()) >= static::GAME_LIMIT;
    }

    private function getMilitaryLimitCount(Player $player)
    {
        $count = 0;
        $scores = $player->getScores();
        if (count($scores)) {
            foreach ($scores as $playerScore) {
                if ($playerScore->getMilitaryScore() >= static::MILITARY_LIMIT) {
                    $count++;
                }
            }

        }
        return $count;
    }

    /**
     * @param Player $player
     * @return Progress
     */
    public function getProgress(Player $player)
    {
        return new Progress($this->getMilitaryLimitCount($player), static::GAME_LIMIT);
    }

}
