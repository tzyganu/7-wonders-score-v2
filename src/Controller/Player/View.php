<?php
namespace App\Controller\Player;

use App\Controller\ListEntities;
use App\Entity\Player;
use App\Grid\Loader;
use App\Block\PlayerAchievement;
use App\Tab;
use App\Tab\ContainerFactory;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class View extends AbstractController
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var Loader
     */
    private $gridLoader;
    /**
     * @var ContainerFactory
     */
    private $containerFactory;
    /**
     * @var PlayerAchievement
     */
    private $playerAchievementBlock;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Loader $gridLoader,
        ContainerFactory $containerFactory,
        PlayerAchievement $playerAchievementsBlock
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->gridLoader = $gridLoader;
        $this->containerFactory = $containerFactory;
        $this->playerAchievementBlock = $playerAchievementsBlock;
    }
    /**
     * @param int $id
     * @return string
     * @Route(
     *      "player/view/{id}",
     *      name="player/view",
     * )
     */
    public function execute($id = null)
    {
        /** @var Player $instance */
        $instance = $this->managerRegistry->getRepository(Player::class)->find($id);
        if (!$instance) {
            $this->addFlash('error', "Player with id {$id} does not exist");
            return $this->redirectToRoute(
                ListEntities::DEFAULT_LIST_PATH,
                [
                    ListEntities::ENTITY_PARAM_NAME => 'player'
                ]
            );
        }
        $tabContainer = $this->containerFactory->create();

        $grid = $this->gridLoader->loadGrid('player-score');
        $grid->removeColumn('player');
        $grid->setRows($instance->getScores());
        $tab = new Tab('Scores', $grid->render());
        $tabContainer->addTab('score-tab', $tab);

        $tab = new Tab('Achievements', $this->playerAchievementBlock->render($instance));
        $tabContainer->addTab('achievement-tab', $tab);
        return $this->render(
            'default.html.twig',
            [
                'title' => $instance->getName(),
                'content' => $tabContainer->render(),
            ]
        );
    }
}
