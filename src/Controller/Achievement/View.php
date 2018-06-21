<?php
namespace App\Controller\Achievement;

use App\Controller\ListEntities;
use App\Entity\Achievement;
use App\Entity\PlayerAchievement;
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
    private $gridLoader;
    private $tabContainerFactory;

    /**
     * View constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Loader $gridLoader,
        ContainerFactory $tabContainerFactory
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->gridLoader = $gridLoader;
        $this->tabContainerFactory = $tabContainerFactory;
    }

    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route(
     *      "achievement/view/{id}",
     *      name="achievement/view"
     * )
     */
    public function execute($id = null)
    {
        /** @var Achievement $instance */
        $instance = $this->managerRegistry->getRepository(Achievement::class)->find($id);
        if (!$instance) {
            $this->addFlash('error', 'Achievement with id '.$id.' does not exist');
            return $this->redirectToRoute(
                ListEntities::DEFAULT_LIST_PATH,
                [
                    ListEntities::ENTITY_PARAM_NAME => 'achievement'
                ]
            );
        }
        $container = $this->tabContainerFactory->create();
        $grid = $this->gridLoader->loadGrid('achievement');
        $grid->removeColumn('view');
        $grid->setTitle('');
        $grid->setUseDataTable(false);
        $grid->setRows([$instance]);

        $viewTab = new Tab('Info', $grid->render());
        $container->addTab('info-tab', $viewTab);

        //players with achievement
        $playerAchievements = $this->managerRegistry->getRepository(PlayerAchievement::class)->findBy(['achievement' => $instance]);
        $playerGrid = $this->gridLoader->loadGrid('achievement-player');
        $playerGrid->setRows($playerAchievements);

        $achievedTab = new Tab('Achieved By', $playerGrid->render());
        $container->addTab('achieved-tab', $achievedTab);

        return $this->render(
            'default.html.twig',
            [
                'title' => $instance->getName(). '('.$instance->getAchievementColor()->getName().')',
                'content' => $container->render(),
            ]
        );
    }
}
