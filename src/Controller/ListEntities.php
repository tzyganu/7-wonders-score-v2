<?php
namespace App\Controller;

use App\EntityMapper;
use App\Grid\Loader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method Response render()
 */
class ListEntities extends AbstractController
{
    const DEFAULT_LIST_PATH = 'entity/list';
    const ENTITY_PARAM_NAME = 'entity';
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var Loader
     */
    private $gridLoader;
    /**
     * @var EntityMapper
     */
    private $entityMapper;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param Loader $gridLoader
     * @param EntityMapper $entityMapper
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Loader $gridLoader,
        EntityMapper $entityMapper
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->gridLoader = $gridLoader;
        $this->entityMapper = $entityMapper;
    }

    /**
     * @param string $entity
     * @return string
     * @Route(
     *      "{entity}/list/",
     *      name="entity/list",
     *      requirements={
     *           "entity": "achievement|achievement-group|achievement-color|category|player|wonder|wonder-set"
     *      }
     * )
     */
    public function execute($entity)
    {
        $grid = $this->gridLoader->loadGrid($entity);
        $repoName = $this->entityMapper->getEntityName($entity);
        $items = $this->managerRegistry->getRepository($repoName)->findAll();
        $grid->setRows($items);
        return $this->render(
            'default.html.twig',
            [
                'content' => $grid->render(),
            ]
        );
    }
}
