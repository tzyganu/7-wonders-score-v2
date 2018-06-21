<?php
namespace App\Game;

use App\Entity\Game;
use App\Entity\Score;

class Ranker
{
    /**
     * @param Game $game
     */
    public function rankScores(Game $game)
    {
        /** @var Score[] $scores */
        $scores = $game->getScores()->toArray();
        uasort($scores, [$this, 'compareScores']);
        $scores = array_reverse($scores, true);
        $rank = 1;
        $index = 1;
        $lastScore = null;
        foreach ($scores as $score) {
            if ($lastScore === null || $this->compareScores($lastScore, $score) !== 0) {
                $rank = $index;
            }
            $score->setRank($rank);
            $lastScore = $score;
            $index++;
        }
        foreach ($scores as $score) {
            $score->setLast($score->getRank() == $rank);
        }
    }

    /**
     * @param Score $a
     * @param Score $b
     * @return int
     */
    private function compareScores(Score $a, Score $b)
    {
        if ($a->getTotalScore() > $b->getTotalScore()) {
            return 1;
        }
        if ($a->getTotalScore() == $b->getTotalScore()) {
            if ($a->getCashCoins() > $b->getCashCoins()) {
                return 1;
            }
            if ($a->getCashCoins() == $b->getCashCoins()) {
                return 0;
            }
            return -1;
        }
        return -1;
    }
}