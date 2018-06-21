<?php
namespace App\Stats;

use App\Achievement\Pool;
use App\Entity\Achievement;
use App\Entity\Player;
use App\Score\CalculatorPool;
use Doctrine\Common\Persistence\ManagerRegistry;

class PlayerAchievements
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var CalculatorPool
     */
    private $pool;

    /**
     * PlayerAchievements constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Pool $pool
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->pool = $pool;
    }

    public function getAchievementsData(Player $player)
    {
        $achievedIds = $player->getAchievementIds();
        $result = [];
        foreach ($this->getAllAchievements() as $achievement) {
            $data = [];
            $data['id'] = $achievement->getId();
            $data['name'] = $achievement->getName();
            $data['identifier'] = $achievement->getIdentifier();
            $data['color'] = strtolower($achievement->getAchievementColor()->getName());
            $data['color_id'] = $achievement->getAchievementColor()->getId();
            $data['group'] = strtolower($achievement->getGroup()->getName());
            $data['group_id'] = strtolower($achievement->getGroup()->getId());
            $data['achieved'] = (int)in_array($achievement->getId(), $achievedIds);
            $data['description'] = $achievement->getDescription();
            $data['progress'] = $this->pool->getCalculator($achievement->getIdentifier())->getProgress($player);
            $result[] = $data;
        }
        return $result;
    }

    /**
     * @return Achievement[]
     */
    private function getAllAchievements()
    {
        return $this->managerRegistry->getRepository(Achievement::class)->findAll();
    }
}
