<?php
namespace App\Controller\Report;

use App\Controller\AuthInterface;
use App\Entity\Report;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class Save extends AbstractController implements AuthInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * Save constructor.
     * @param RequestStack $requestStack
     * @param $managerRegistry
     */
    public function __construct(RequestStack $requestStack, ManagerRegistry $managerRegistry)
    {
        $this->requestStack = $requestStack;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @return string
     * @Route("/report/save", name="report/save")
     */
    public function execute()
    {
        $action = $this->requestStack->getCurrentRequest()->get('save');
        $rules = $this->requestStack->getCurrentRequest()->get('rules');
        $columns = $this->requestStack->getCurrentRequest()->get('columns', []);
        $columns = json_encode($columns);
        $name = $this->requestStack->getCurrentRequest()->get('name');
        $id = $this->requestStack->getCurrentRequest()->get('current_report_id');
        if ($id && $action == General::UPDATE) {
            $report = $this->managerRegistry->getRepository(Report::class)->find($id);
            if (!$report) {
                $report = new Report();
            }
        } else {
            $report = new Report();
        }
        $report->setColumns($columns);
        $report->setRules($rules);
        $report->setName($name);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($report);
        $manager->flush();
        $this->addFlash('success', "Report has been saved");
        return $this->redirectToRoute('report/general', ['report_id' => $report->getId()]);
    }
}
