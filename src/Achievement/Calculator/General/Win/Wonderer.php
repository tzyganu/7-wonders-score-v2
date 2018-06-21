<?php
namespace App\Achievement\Calculator\General\Win;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Score;
use App\Entity\Wonder;

class Wonderer implements CalculatorInterface
{
    const WINS_LIMIT = 3;

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        return count($this->getWins($score->getPlayer())) >= $this->getWinsLimit();
    }

    /**
     * @param Player $player
     * @return Progress
     */
    public function getProgress(Player $player)
    {
        $max = 0;
        $wins = count($this->getWins($player));
        return new Progress($wins, $this->getWinsLimit());
    }

    /**
     * @return int
     */
    protected function getWinsLimit()
    {
        return static::WINS_LIMIT;
    }

    /**
     * @param Player $player
     * @return array
     */
    protected function getWins(PLayer $player)
    {
        $allScores = $player->getScores();
        $wins = [];
        foreach ($allScores as $_score) {
            if ($_score->getRank() != 1) {
                continue;
            }
            $wonder = $_score->getWonder();
            $winKey = $this->getWinKey($_score);
            if (isset($wins[$winKey])) {
                continue;
            }
            if (!$this->isValidWonder($wonder)) {
                continue;
            }
            $wins[$winKey] = 1;
        }
        return $wins;
    }

    /**
     * @param Score $score
     * @return string
     */
    protected function getWinKey(Score $score)
    {
        return $score->getWonder()->getId();
    }

    /**
     * @param Wonder $wonder
     * @return int
     */
    protected function isValidWonder(Wonder $wonder)
    {
        return $wonder->getOfficial();
    }

}
