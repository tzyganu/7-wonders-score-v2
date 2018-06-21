<?php
namespace App\Stats;

use App\Entity\Game;
use App\Entity\Score;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;

class Player
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * Player constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function getPlayerStats($filters)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->managerRegistry->getRepository(Score::class)->createQueryBuilder('score');
        $hasDateFilter = isset($filters['date_from']) || isset($filters['date_to']);
        if ($hasDateFilter) {
            $queryBuilder->leftJoin(Game::class, 'game', Join::WITH, 'score.game = game.id');
        }
        if (isset($filters['date_from'])) {
            $queryBuilder->andWhere('game.playedOn >= :date_from')->setParameter('date_from', $filters['date_from']);
        }
        if (isset($filters['date_to'])) {
            $queryBuilder->andWhere('game.playedOn <= :date_to')->setParameter('date_to', $filters['date_to']);
        }
        if (isset($filters['player']) && count($filters['player'])) {
            $queryBuilder->andWhere('score.player IN (:player)')->setParameter('player', $filters['player']);
        }
        if (isset($filters['wonder']) && count($filters['wonder'])) {
            $queryBuilder->andWhere('score.wonder IN (:wonder)')->setParameter('wonder', $filters['wonder']);
        }
        if (isset($filters['player_count']) && count($filters['player_count'])) {
            $queryBuilder->andWhere('score.playerCount IN (:playerCount)')->setParameter('playerCount', $filters['player_count']);
        }
        return $this->prepareResults($queryBuilder->getQuery()->getResult(), $filters);
    }

    /**
     * @param Score[] $results
     * @param array $filters
     * @return array
     */
    private function prepareResults($results, $filters)
    {
        $processed = [];
        foreach ($results as $result) {
            $key = $this->getResultKey($result, $filters);
            if (!isset($processed[$key])) {
                $processed[$key] = [
                    'player_name' => $result->getPlayer()->getName(),
                    'player_id' => $result->getPlayer()->getId(),
                    'wonder_name' => $result->getWonder()->getName(),
                    'wonder_id' => $result->getWonder()->getId(),
                    'side' => $result->getSide(),
                    'player_count' => $result->getPlayerCount(),
                    'played' => 0,
                    'won' => 0,
                    'last' => 0,
                    'win_percentage' => 0,
                    'last_percentage' => 0,
                    'total_score' => 0,
                    'average_score' => 0,
                    'max_points' => PHP_INT_MIN ,
                    'min_points' => PHP_INT_MAX
                ];
            }
            $processed[$key]['played']++;
            $processed[$key]['won'] += ($result->getRank() == 1) ? 1 : 0;
            $processed[$key]['last'] += ($result->getRank() == $result->getPlayerCount()) ? 1 : 0;
            $processed[$key]['total_score'] += $result->getTotalScore();
            $processed[$key]['max_points'] = ($result->getTotalScore() > $processed[$key]['max_points'])
                ? $result->getTotalScore()
                : $processed[$key]['max_points'];
            $processed[$key]['min_points'] = ($result->getTotalScore() < $processed[$key]['min_points'])
                ? $result->getTotalScore()
                : $processed[$key]['min_points'];
        }
        foreach ($processed as $key => $values) {
            $processed[$key]['won_percentage'] = $values['won'] * 100 / $values['played'];
            $processed[$key]['last_percentage'] = $values['last'] * 100 / $values['played'];
            $processed[$key]['average_score'] = $values['total_score'] / $values['played'];
        }
        return $processed;
    }

    /**
     * @param Score $score
     * @param $filters
     * @return string
     */
    private function getResultKey(Score $score, $filters)
    {
        $key = [];
        if ($filters['group_by_player']) {
            $key[] = $score->getPlayer()->getId();
        }
        if ($filters['group_by_wonder']) {
            $key[] = $score->getWonder()->getId();
        }
        if ($filters['group_by_side']) {
            $key[] = $score->getSide();
        }
        if ($filters['group_by_player_count']) {
            $key[] = $score->getPlayerCount();
        }
        return implode('-', $key);
    }
}
