<?php
namespace App\Achievement;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\PlayerAchievement;
use App\Entity\Score;
use Doctrine\Common\Persistence\ManagerRegistry;

class Checker
{
    /**
     * @var Pool
     */
    private $pool;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * Checker constructor.
     * @param Pool $pool
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        Pool $pool,
        ManagerRegistry $managerRegistry
    ) {
        $this->pool = $pool;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param Game $game
     * @return PlayerAchievement[]
     */
    public function check(Game $game)
    {
        $playerAchievements = [];
        $scores = $game->getScores();
        foreach ($this->getAchievements() as $achievement) {
            foreach ($scores as $score) {
                $playerAchievement = $this->checkAchievement($achievement, $score);
                if ($playerAchievement) {
                    $playerAchievements[] = $playerAchievement;
                }
            }
        }
        return $playerAchievements;
    }

    /**
     * @return Achievement[]
     */
    private function getAchievements()
    {
        return $this->managerRegistry->getRepository(Achievement::class)->findAll();
    }

    /**
     * @param Achievement $achievement
     * @param Score $score
     * @return PlayerAchievement|bool
     */
    private function checkAchievement(Achievement $achievement, Score $score)
    {
        //if player already has achievement
        if (in_array($achievement->getId(), $score->getPlayer()->getAchievementIds())) {
            return false;
        }
        $calculator = $this->pool->getCalculator($achievement->getIdentifier());
        if (!$calculator->validate($score)) {
            return false;
        }
        $playerAchievement = new PlayerAchievement();
        $playerAchievement->setAchievement($achievement);
        $playerAchievement->setScore($score);
        $playerAchievement->setPlayer($score->getPlayer());
        return $playerAchievement;
    }
}
