<?php
namespace App\Controller\Game;

use App\Entity\Game;
use App\Grid\Loader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ListGame extends AbstractController
{
    /**
     * @var Loader
     */
    private $gridLoader;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * ListGame constructor.
     * @param Loader $gridLoader
     */
    public function __construct(Loader $gridLoader, ManagerRegistry $managerRegistry)
    {
        $this->gridLoader = $gridLoader;
        $this->managerRegistry = $managerRegistry;

    }


    /**
     * @return string
     * @Route(
     *      "game/list/",
     *      name="game/list"
     * )
     */
    public function execute()
    {
        $grid = $this->gridLoader->loadGrid('game');
        $grid->setRows($this->managerRegistry->getRepository(Game::class)->findAll());
        return $this->render(
            'default.html.twig',
            [
                'content' => $grid->render(),
            ]
        );
    }
}
