<?php
namespace App\Block;

use App\Entity\AchievementColor;
use App\Entity\AchievementGroup;
use App\Entity\Player;
use App\Stats\PlayerAchievements;
use Doctrine\Common\Persistence\ManagerRegistry;

class PlayerAchievement
{
    /**
     * @var \Twig_Environment;
     */
    private $twig;
    /**
     * @var PlayerAchievements
     */
    private $playerAchievementStats;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var string
     */
    private $template;

    /**
     * PlayerAchievement constructor.
     * @param \Twig_Environment $twig
     * @param PlayerAchievements $playerAchievementStats
     * @param string $template
     */
    public function __construct(
        \Twig_Environment $twig,
        PlayerAchievements $playerAchievementStats,
        ManagerRegistry $managerRegistry,
        $template = 'stats/player/achievements.html.twig'
    ) {
        $this->twig = $twig;
        $this->playerAchievementStats = $playerAchievementStats;
        $this->managerRegistry = $managerRegistry;
        $this->template = $template;
    }

    /**
     * @param Player $player
     * @return string
     */
    public function render(Player $player)
    {
        return $this->twig->render(
            $this->template,
            [
                'player' => $player,
                'achievements' => $this->playerAchievementStats->getAchievementsData($player),
                'groups' => $this->managerRegistry->getRepository(AchievementGroup::class)->findAll(),
                'colors' => $this->managerRegistry->getRepository(AchievementColor::class)->findAll(),
            ]
        );
    }
}
