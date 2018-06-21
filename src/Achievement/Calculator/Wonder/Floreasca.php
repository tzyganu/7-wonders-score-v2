<?php
namespace App\Achievement\Calculator\Wonder;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Score;

class Floreasca implements CalculatorInterface
{
    const STAGE_LIMIT = 37;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $this->getTotalStages($score->getPlayer()) >= static::STAGE_LIMIT;
    }

    /**
     * @param Player $player
     * @return int
     */
    private function getTotalStages(Player $player)
    {
        $count = 0;
        $scores = $player->getScores();
        if ($scores) {
            foreach ($scores as $_score) {
                $count += $_score->getWonderStage();
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
        return new Progress($this->getTotalStages($player), static::STAGE_LIMIT);
    }
}
