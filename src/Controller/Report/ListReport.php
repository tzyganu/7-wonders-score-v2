<?php
namespace App\Controller\Report;

use App\Entity\Report;
use App\EntityMapper;
use App\Grid\Loader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ListReport extends AbstractController
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
     * @param ManagerRegistry $managerRegistry
     * @param Loader $gridLoader
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Loader $gridLoader
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->gridLoader = $gridLoader;
    }

    /**
     * @param string $entity
     * @return string
     * @Route(
     *      "report/list/",
     *      name="report/list"
     * )
     */
    public function execute()
    {
        $grid = $this->gridLoader->loadGrid('report');
        $items = $this->managerRegistry->getRepository(Report::class)->findAll();
        $grid->setRows($items);
        return $this->render(
            'default.html.twig',
            [
                'content' => $grid->render(),
            ]
        );
    }
}
