<?php
namespace App\Controller\Wonder;

use App\Controller\ListEntities;
use App\Entity\Player;
use App\Entity\Wonder;
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
     * @var \App\Stats\Wonder
     */
    private $wonderStats;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Loader $gridLoader,
        ContainerFactory $containerFactory,
        \App\Stats\Wonder $wonderStats
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->gridLoader = $gridLoader;
        $this->containerFactory = $containerFactory;
        $this->wonderStats = $wonderStats;
    }
    /**
     * @param int $id
     * @return string
     * @Route(
     *      "wonder/view/{id}",
     *      name="wonder/view",
     * )
     */
    public function execute($id = null)
    {
        /** @var Wonder $instance */
        $instance = $this->managerRegistry->getRepository(Wonder::class)->find($id);
        if (!$instance) {
            $this->addFlash('error', "Wonder with id {$id} does not exist");
            return $this->redirectToRoute(
                ListEntities::DEFAULT_LIST_PATH,
                [
                    ListEntities::ENTITY_PARAM_NAME => 'wonder'
                ]
            );
        }
        $tabContainer = $this->containerFactory->create();

        $grid = $this->gridLoader->loadGrid('wonder-score');
        $grid->setRows($instance->getScores());
        $tab = new Tab('Scores', $grid->render());
        $tabContainer->addTab('score-tab', $tab);

        $achievementsGrid = $this->gridLoader->loadGrid('wonder-achievement');
        $achievementsGrid->setRows($instance->getPlayerAchievements());
        $tab = new Tab('Achievements', $achievementsGrid->render());
        $tabContainer->addTab('achievement-tab', $tab);

        $stats = [$this->wonderStats->getStats($instance)];
        $statsGrid = $this->gridLoader->loadGrid('wonder-stats');
        $statsGrid->setRows($stats);

        $rankGrid = $this->gridLoader->loadGrid('wonder-ranks');
        $rankGrid->setRows($stats);
        $tab = new Tab('Stats', $statsGrid->render().$rankGrid->render());
        $tabContainer->addTab('stats-tab', $tab);

        return $this->render(
            'default.html.twig',
            [
                'title' => $instance->getName(),
                'content' => $tabContainer->render(),
            ]
        );
    }
}
