<?php
namespace App\Controller\Game;

use App\Controller\AuthInterface;
use App\Entity\Category;
use App\Entity\Player;
use App\Entity\Wonder;
use App\Form\ScoreRendererPool;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use App\Player\Settings;
use App\Score\Calculator\Cash as CashCalculator;
use App\Score\Calculator\Military;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\WonderSet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewGame extends AbstractController implements AuthInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var ScoreRendererPool
     */
    private $scoreRendererPool;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        ScoreRendererPool $scoreRendererPool
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->scoreRendererPool = $scoreRendererPool;
    }

    /**
     * @param null $count
     * @return string
     * @Route(
     *      "game/new/{count}",
     *      name="game/new",
     *      requirements={
     *          "count" = "\d+"
     *      },
     *      defaults={
     *          "count"=Settings::DEFAULT_PLAYERS
     *      }
     * )
     */
    public function execute($count)
    {
        /** @var WonderSet[] $wondersSets */
        $wondersSets = $this->managerRegistry->getRepository(WonderSet::class)->findAll();
        /** @var Player[] $players */
        $players = $this->managerRegistry->getRepository(Player::class)->findBy([
            'active' => 1
        ], ['name' => 'ASC']);
        /** @var Category[] $categories */
        $categories = $this->managerRegistry->getRepository(Category::class)->findBy(
            [],
            ['sortOrder' => 'ASC']
        );
        $formBuilder = $this->createFormBuilder();
        $gameForm = $formBuilder->create(
            'game',
            \App\Form\Game::class,
            [
                'data' => [
                    'played_on' => new \DateTime(),
                    'leaders' => true,
                    'cities' => true,
                    'playLeft' => true,
                ]
            ]
        );
        $formBuilder->add($gameForm);
        $templateForm = $formBuilder->create('__id__', FormType::class);
        $templateForm->add('player', \App\Form\Score\Player::class);
        foreach ($categories as $category) {
            $templateForm->add(
                $formBuilder->create(
                    $category->getCode(),
                    $this->scoreRendererPool->getFormRenderer($category->getCode())
                )
            );
        }
        $templateForm->add('total', \App\Form\Score\Total::class);
        $formBuilder->add($templateForm);
        $formBuilder->setAction($this->generateUrl('game/save'));
        return $this->render(
            'game/new.html.twig',
            [
                'wonderSets' => $wondersSets,
                'players' => $players,
                'playerCount' => $count,
                'form' => $formBuilder->getForm()->createView(),
                'wondersConfig' => $this->getWondersConfig(),
            ]
        );
    }

    /**
     * @return array
     */
    private function getWondersConfig()
    {
        $config = [];
        $wonders = $this->managerRegistry->getRepository(Wonder::class)->findAll();
        foreach ($wonders as $wonder) {
            /** @var Wonder $wonder */
            $config[$wonder->getId()] = [
                'id' => $wonder->getId(),
                'name' => $wonder->getName(),
                'leaders' => $wonder->getPlayableWithLeaders(),
                'withoutLeaders' => $wonder->getPlayableWithoutLeaders(),
                'cities' => $wonder->getPlayableWithCities(),
                'withoutCities' => $wonder->getPlayableWithoutCities()
            ];
        }
        return json_encode($config);
    }
}
