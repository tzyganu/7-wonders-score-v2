<?php
namespace App\Controller;

use App\Entity\Score;
use App\Tab;
use App\Tab\ContainerFactory;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Dashboard extends AbstractController
{
    /**
     * @var ContainerFactory
     */
    private $containerFactory;
    /**
     * @var \App\Stats\Score
     */
    private $scoreStats;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Dashboard constructor.
     * @param ContainerFactory $containerFactory
     * @param \App\Stats\Score $scoreStats
     */
    public function __construct(
        ContainerFactory $containerFactory,
        \App\Stats\Score $scoreStats,
        \Twig_Environment $twig
    ) {
        $this->containerFactory = $containerFactory;
        $this->scoreStats = $scoreStats;
        $this->twig = $twig;
    }


    /**
     * @return string
     * @Route("/", name="/")
     * @Route("/dashboard/", name="dashboard")
     */
    public function execute()
    {
        $container = $this->containerFactory->create();
        $container->addTab('high', new Tab('High scores', $this->getHighScoresTab()));
        $container->addTab('average', new Tab('Average scores', $this->getAverageScoresTab()));
        $container->addTab('low', new Tab('Low scores', $this->getLowScoresTab()));

        return $this->render('default.html.twig', ['title' => 'Dashboard', 'content' => $container->render()]);
    }

    private function getStatsTab($function, $withPlayer)
    {
        $data = $this->scoreStats->getStats($function, $withPlayer);
        $content = '';
        foreach ($data as $category => $scores) {
            foreach ($scores as $scoreKey => $scoreData) {
                $content .= $this->twig->render('dashboard/stat.html.twig', $scoreData);
            }
        }
        return $content;
    }

    private function getHighScoresTab()
    {
        return $this->getStatsTab('MAX', true);
    }

    private function getAverageScoresTab()
    {
        return $this->getStatsTab("AVG", false);
    }

    private function getLowScoresTab()
    {
        return $this->getStatsTab("MIN", true);
    }
}
