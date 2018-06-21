<?php
namespace App\Achievement\Calculator\General\Played;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Player;
use App\Entity\Score;

class Rookie implements CalculatorInterface
{
    const LIMIT = 5;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return $this->getScoresCount($score->getPlayer()) >= static::LIMIT;
    }

    /**
     * @param Player $player
     * @return int
     */
    private function getScoresCount(Player $player)
    {
        $scores = $player->getScores();
        return ($scores === null) ? 0 : $scores->count();
    }

    /**
     * @param Player $player
     * @return Progress
     */
    public function getProgress(Player $player)
    {
        return new Progress($this->getScoresCount($player), static::LIMIT);
    }
}
