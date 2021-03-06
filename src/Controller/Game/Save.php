<?php
namespace App\Controller\Game;

use App\Achievement\Checker;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Score;
use App\Entity\Wonder;
use App\Game\Ranker;
use App\Score\CalculatorPool;
use App\Util\StringUtils;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Category;
use App\AuthValidator;

class Save extends AbstractController
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
     * @var \App\Score\CalculatorPool
     */
    private $calculatorPool;
    /**
     * @var Ranker
     */
    private $ranker;
    /**
     * @var Checker
     */
    private $achievementChecker;
    /**
     * @var AuthValidator
     */
    private $authValidator;
    /**
     * @var StringUtils
     */
    private $stringUtils;

    /**
     * Save constructor.
     * @param RequestStack $requestStack
     * @param ManagerRegistry $managerRegistry
     * @param CalculatorPool $calculatorPool
     * @param Ranker $ranker
     * @param Checker $checker
     * @param AuthValidator $authValidator
     * @param StringUtils $stringUtils
     */
    public function __construct(
        RequestStack $requestStack,
        ManagerRegistry $managerRegistry,
        CalculatorPool $calculatorPool,
        Ranker $ranker,
        Checker $checker,
        AuthValidator $authValidator,
        StringUtils $stringUtils
    ) {
        $this->requestStack = $requestStack;
        $this->managerRegistry = $managerRegistry;
        $this->calculatorPool = $calculatorPool;
        $this->ranker = $ranker;
        $this->achievementChecker = $checker;
        $this->authValidator = $authValidator;
        $this->stringUtils = $stringUtils;
    }

    /**
     * @return string
     * @Route(
     *      "game/save/",
     *      name="game/save"
     * )
     */
    public function execute()
    {
        $position = 1;
        $postData = $this->requestStack->getCurrentRequest()->request->get('form');
        $game = new Game();
        $game->setPlayedOn(new \DateTime($postData['game']['played_on']));
        $game->setLeaders(isset($postData['game']['leaders']));
        $game->setCities(isset($postData['game']['cities']));
        $game->setPlayLeft(isset($postData['game']['playLeft']));
        $game->setCanExclude(isset($postData['game']['canExclude']));
        $game->setUser($this->authValidator->getUser());
        /** @var Score[] $scoresByPosition */
        $scoresByPosition = [];
        unset($postData['game']);
        foreach ($postData as $playerData) {
            /** @var Player $player */
            $player = $this->managerRegistry->getRepository(Player::class)->find($playerData['player']['player_id']);
            $score = new Score();
            $score->setPlayer($player);
            /** @var Wonder $wonder */
            $wonder = $this->managerRegistry->getRepository(Wonder::class)->find($playerData['player']['wonder_id']);
            $score->setWonder($wonder);
            $score->setSide($playerData['player']['side']);
            $score->setGame($game);
            $score->setPlayerCount(count($postData));
            $categories = $this->managerRegistry->getRepository(Category::class)->findAll();
            $totalScore = 0;
            foreach ($categories as $category) {
                /** @var Category $category */
                if ($category->getOptional()) {
                    $getter = 'get'.ucfirst($category->getCode());
                    if (!$game->$getter()) {
                        $setter = 'set'.ucfirst($category->getCode()).'Score';
                        $score->$setter(null);
                        continue;
                    }
                }
                $calculator = $this->calculatorPool->getCalculator($category->getCode());
                $categoryScore = $calculator->calculate($playerData[$category->getCode()]);
                $totalScore += $categoryScore;
                $playerData[$category->getCode()]['score'] = $categoryScore;
                foreach ($playerData[$category->getCode()] as $key => $value) {
                    $method = $this->stringUtils->camelize('set_'.$category->getCode().'_'.$key);
                    $score->$method($value);
                }
            }
            $score->setTotalScore($totalScore);
            $score->setPosition($position);
            $scoresByPosition[$position] = $score;
            $position++;
            $game->addScore($score);
        }
        $playerCount = count($game->getScores());
        $this->ranker->rankScores($game);
        $game->setPlayerCount($playerCount);
        //attach lef-right to scores
        $playLeft = $game->getPlayLeft();
        foreach ($scoresByPosition as $position => $score) {
            $leftPosition = (($score->getPosition() - 1  + $playerCount +  (($playLeft) ? 1 : -1)) % $playerCount) + 1;
            $rightPosition = (($score->getPosition() - 1  + $playerCount +  (($playLeft) ? -1 : 1)) % $playerCount) + 1;
            $leftScore = $scoresByPosition[$leftPosition];
            $rightScore = $scoresByPosition[$rightPosition];
            $score->setLeftPlayer($leftScore->getPlayer());
            $score->setLeftWonder($leftScore->getWonder());
            $score->setLeftSide($leftScore->getSide());
            $score->setLeftRank($leftScore->getRank());
            $score->setRightPlayer($rightScore->getPlayer());
            $score->setRightWonder($rightScore->getWonder());
            $score->setRightSide($rightScore->getSide());
            $score->setRightRank($rightScore->getRank());
        }
        $this->getDoctrine()->getManager()->persist($game);

        $this->getDoctrine()->getManager()->flush();
        //check achievements
        $playerAchievements = $this->achievementChecker->check($game);
        if (count($playerAchievements)) {
            foreach ($playerAchievements as $playerAchievement) {
                $this->getDoctrine()->getManager()->persist($playerAchievement);
            }
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('game/view', ['id' => $game->getId()]);
    }
}
