<?php
namespace App\Stats;

class Wonder
{
    const PLAYED = 'played';
    const WON = 'won';
    const RANK = 'rank';
    const RANK_PERCENT = 'rank_percent';
    const LAST = 'last';
    const AVERAGE_SCORE = 'average_score';
    const TOTAL_POINTS = 'total_points';

    /**
     * @param \App\Entity\Wonder $wonder
     * @return array
     */
    public function getStats(\App\Entity\Wonder $wonder)
    {
        $stats = [
            self::PLAYED => 0,
            self::TOTAL_POINTS => 0,
            self::RANK => [
                'last' => 0
            ],
            self::RANK_PERCENT => [
                'last' => 0
            ],
            self::AVERAGE_SCORE => 0,
        ];
        foreach (range(1,8) as $rank) {
            $stats[self::RANK][$rank] = 0;
            $stats[self::RANK_PERCENT][$rank] = 0;
        }
        foreach ($wonder->getScores() as $score) {
            $stats[self::PLAYED]++;
            if (!isset($stats[self::RANK][$score->getRank()])) {
                $stats[self::RANK][$score->getRank()] = 0;
            }
            $stats[self::RANK][$score->getRank()]++;
            if ($score->getRank() == $score->getGame()->getPlayerCount()) {
                $stats[self::RANK][self::LAST]++;
            }
            $stats[self::TOTAL_POINTS] += $score->getTotalScore();
        }
        if ($stats[self::PLAYED] != 0) {
            $stats[self::AVERAGE_SCORE] = $stats[self::TOTAL_POINTS]/ $stats[self::PLAYED];
            foreach ($stats[self::RANK] as $rank => $count) {
                $stats[self::RANK_PERCENT][$rank] = $count * 100 / $stats[self::PLAYED];
            }
        }
        return $stats;
    }
}
