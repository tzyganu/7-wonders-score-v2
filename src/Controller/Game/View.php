<?php
namespace App\Controller\Game;

use App\Button\Factory;
use App\Controller\ListEntities;
use App\Entity\Game;
use App\Grid\Loader;
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
     * @var Factory
     */
    private $buttonFactory;

    /**
     * View constructor.
     * @param ManagerRegistry $managerRegistry
     * @param Loader $gridLoader
     * @param ContainerFactory $containerFactory
     * @param Factory $buttonFactory
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Loader $gridLoader,
        ContainerFactory $containerFactory,
        Factory $buttonFactory
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->gridLoader = $gridLoader;
        $this->containerFactory = $containerFactory;
        $this->buttonFactory = $buttonFactory;
    }
    /**
     * @param int $id
     * @return string
     * @Route(
     *      "game/view/{id}",
     *      name="game/view",
     * )
     */
    public function execute($id = null)
    {
        /** @var Game $instance */
        $instance = $this->managerRegistry->getRepository(Game::class)->find($id);
        if (!$instance) {
            $this->addFlash('error', "Game with id {$id} does not exist");
            return $this->redirectToRoute(
                ListEntities::DEFAULT_LIST_PATH,
                [
                    ListEntities::ENTITY_PARAM_NAME => 'game'
                ]
            );
        }

        $grid = $this->gridLoader->loadGrid('game-view');
        $grid->setRows($instance->getScores());
        $grid->addButton('new-game', $this->getNewGameButton($id));
        $grid->removeColumn('game');
        if (!$instance->getCities()) {
            $grid->removeColumn('cities_score');
        }
        if (!$instance->getLeaders()) {
            $grid->removeColumn('leaders_score');
        }
        $tab = new Tab('Game Scores', $grid->render());
        $tabsContainer = $this->containerFactory->create();
        $tabsContainer->addTab('game-scores-tab', $tab);

        $achievementsGrid = $this->gridLoader->loadGrid('game-achievement');
        $scores = $instance->getScores();
        $allAchievements = [];
        foreach ($scores as $score) {
            $achievements = $score->getPlayerAchievements();
            foreach ($achievements as $achievement) {
                $allAchievements[] = $achievement;
            }
        }
        $achievementsGrid->setRows($allAchievements);

        $tab = new Tab('Achievements', $achievementsGrid->render());
        $tabsContainer->addTab('game-achievements-tab', $tab);
        return $this->render(
            'default.html.twig',
            [
                'title' => 'Game: '.$instance->getId().': '.$instance->getPlayedOn()->format('Y-m-d'),
                'content' => $tabsContainer->render(),
            ]
        );
    }

    /**
     * @param $id
     * @return \App\Button
     */
    private function getNewGameButton($id)
    {
        return $this->buttonFactory->create([
            'label' => 'New Game Same Players',
            'url' => 'game/new',
            'params' => [
                'id' => $id
            ]
        ]);

    }
}
