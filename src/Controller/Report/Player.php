<?php
namespace App\Controller\Report;

use App\FormWrapper\Factory;
use App\FormWrapper\Loader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class Player extends AbstractController
{
    /**
     * @var Loader
     */
    private $formLoader;
    /**
     * @var RequestStack;
     */
    private $requestStack;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var \App\Stats\Player
     */
    private $playerStats;
    /**
     * @var array
     */
    private $filters;
    /**
     * @var \App\Grid\Loader
     */
    private $gridLoader;

    /**
     * Player constructor.
     * @param Loader $formLoader
     * @param RequestStack $requestStack
     * @param ManagerRegistry $managerRegistry
     * @param \App\Stats\Player $playerStats
     */
    public function __construct(
        Loader $formLoader,
        RequestStack $requestStack,
        ManagerRegistry $managerRegistry,
        \App\Stats\Player $playerStats,
        \App\Grid\Loader $gridLoader
    ) {
        $this->formLoader = $formLoader;
        $this->requestStack = $requestStack;
        $this->managerRegistry = $managerRegistry;
        $this->playerStats = $playerStats;
        $this->gridLoader = $gridLoader;
    }


    /**
     * @return string
     * @Route("/report/player", name="report/player")
     */
    public function execute()
    {
        $form = $this->formLoader->loadForm('report/player', $this->getFilterValues());
        $content = $form->render();
        $filters = $this->getFilterValues();
        if ($filters) {
            $grid = $this->gridLoader->loadGrid('report/player');
            if (!$filters['group_by_player']) {
                $grid->removeColumn('player');
            }
            if (!$filters['group_by_wonder']) {
                $grid->removeColumn('wonder');
            }
            if (!$filters['group_by_side']) {
                $grid->removeColumn('side');
            }
            if (!$filters['group_by_player_count']) {
                $grid->removeColumn('player_count');
            }
            if (!$filters['group_by_player'] && !$filters['group_by_side'] && !$filters['group_by_wonder']) {
                $grid->removeColumn('won');
                $grid->removeColumn('won_percentage');
                $grid->removeColumn('last');
                $grid->removeColumn('last_percentage');
            }
            $grid->setRows($this->getReportData());
            $content .= $grid->render();
        }
        return $this->render('report.html.twig', [
            'content' => $content
        ]);
    }

    private function getFilterValues()
    {
        if ($this->filters === null) {
            $values = $this->requestStack->getCurrentRequest()->get('report');
            foreach (['date_from', 'date_to'] as $date) {
                if ($values[$date]) {
                    $values[$date] = new \DateTime($values[$date]);
                } else {
                    unset($values[$date]);
                }
            }
            if (isset($values['player']) && count($values['player'])) {
                $values['player'] = $this->managerRegistry->getRepository(\App\Entity\Player::class)->findBy(['id' => $values['player']]);
            }
            if (isset($values['wonder']) && count($values['wonder'])) {
                $values['wonder'] = $this->managerRegistry->getRepository(\App\Entity\Wonder::class)->findBy(['id' => $values['wonder']]);
            }
            foreach (['group_by_player', 'group_by_wonder', 'group_by_side', 'group_by_player_count'] as $group) {
                $values[$group] = isset($values[$group]);
            }
            $this->filters = $values;
        }
        return $this->filters;
    }

    /**
     * @return mixed
     */
    private function getReportData()
    {
        return $this->playerStats->getPlayerStats($this->getFilterValues());
    }
}
