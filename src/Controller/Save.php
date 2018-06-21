<?php
namespace App\Controller;

use App\Entity\Player;
use App\EntityMapper;
use App\FormWrapper;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class Save extends AbstractController implements AuthInterface
{
    const DEFAULT_SAVE_PATH = 'entity/save';
    const ENTITY_PARAM_NAME = 'entity';
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;
    /**
     * @var \App\EntityMapper
     */
    private $entityMapper;
    /**
     * @var FormWrapper\Loader
     */
    private $formWrapperLoader;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param RequestStack $requestStack
     * @param EntityMapper $entityMapper
     * @param FormWrapper\Loader $formWrapperLoader
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        RequestStack $requestStack,
        EntityMapper $entityMapper,
        FormWrapper\Loader $formWrapperLoader
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->requestStack = $requestStack;
        $this->entityMapper = $entityMapper;
        $this->formWrapperLoader = $formWrapperLoader;
    }

    /**
     * @param string $entity
     * @return string
     * @Route(
     *      "{entity}/save/",
     *      name="entity/save",
     *      requirements={
     *           "entity": "achievement|achievement-group|achievement-color|category|player|wonder|wonder-set"
     *      },
     *      methods={"POST"}
     * )
     */
    public function execute($entity)
    {
        $data = $this->requestStack->getCurrentRequest()->request->get($entity);
        $id = $data['id'] ?? null;
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
        $form = $this->formWrapperLoader->loadForm($entity, $instance)->getForm();
        $form->handleRequest($this->requestStack->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Doctrine\ORM\EntityManager $manager */
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($instance);
            $manager->flush();
            $this->addFlash('success', $this->entityMapper->getSaveMessage($entity));
        }
        return $this->redirectToRoute(
            ListEntities::DEFAULT_LIST_PATH,
            [
                ListEntities::ENTITY_PARAM_NAME => $entity
            ]
        );
    }
}
