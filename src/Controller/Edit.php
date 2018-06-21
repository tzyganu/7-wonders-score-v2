<?php
namespace App\Controller;

use App\EntityMapper;
use App\FormWrapper\Loader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Edit extends AbstractController implements AuthInterface
{
    const DEFAULT_NEW_PATH = 'entity/new';
    const ENTITY_PARAM_NAME = 'entity';
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var \App\FormWrapper\Loader
     */
    private $formLoader;
    /**
     * @var EntityMapper
     */
    private $entityMapper;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param Loader $formLoader
     * @param EntityMapper $entityMapper
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Loader $formLoader,
        EntityMapper $entityMapper
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->formLoader = $formLoader;
        $this->entityMapper = $entityMapper;
    }

    /**
     * @param string $entity
     * @param int $id
     * @return string
     * @Route(
     *      "{entity}/new",
     *      name="entity/new",
     *      requirements={
     *           "entity": "achievement-group|achievement-color|category|player|wonder|wonder-set",
     *      }
     * )
     * @Route(
     *      "{entity}/edit/id/{id}",
     *      name="entity/edit",
     *      requirements={
     *           "entity": "achievement|achievement-group|achievement-color|category|player|wonder|wonder-set",
     *           "id" = "\d+"
     *      },
     *      defaults={
     *          "id"=0
     *      }
     * )
     */
    public function execute($entity, $id = null)
    {
        $entityClass = $this->entityMapper->getEntityName($entity);
        if ($id) {
            $instance = $this->managerRegistry->getRepository($entityClass)->find($id);
            if (!$instance) {
                $this->addFlash('error', $this->entityMapper->getNotExistsMessage($entity, $id));
                return $this->redirectToRoute(
                    ListEntities::DEFAULT_LIST_PATH,
                    [
                        ListEntities::ENTITY_PARAM_NAME => $entity
                    ]
                );
            }
        } else {
            $instance = new $entityClass();
        }

        $formWrapper = $this->formLoader->loadForm($entity, $instance);
        return $this->render(
            'default.html.twig',
            [
                'content' => $formWrapper->render(),
            ]
        );
    }
}
