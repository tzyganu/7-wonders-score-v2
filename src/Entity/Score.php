<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity
 * @Table(name="score",
 *    uniqueConstraints={
 *        @UniqueConstraint(name="unique_game_player",
 *            columns={"game_id", "player_id"}),
 *        @UniqueConstraint(name="unique_game_wonder",
 *            columns={"game_id", "wonder_id"}),
 *    }
 * )
 */
class Score
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var Game
     * @ManyToOne(targetEntity="Game", cascade={"all"}, fetch="LAZY", inversedBy="scores")
     * @ORM\JoinColumn(
     *      name="game_id",
     *      referencedColumnName="id",
     *      nullable=false,
     *      onDelete="CASCADE"
     * )
     */
    private $game;
    /**
     * @var Player
     * @ManyToOne(targetEntity="Player", fetch="LAZY", inversedBy="scores", cascade={"persist"})
     * @ORM\JoinColumn(
     *      name="player_id",
     *      referencedColumnName="id",
     *      nullable=false,
     *      onDelete="CASCADE"
     * )
     */
    private $player;
    /**
     * @var Wonder
     * @ManyToOne(targetEntity="Wonder", fetch="LAZY",cascade={"persist"}, inversedBy="scores")
     * @ORM\JoinColumn(
     *      name="wonder_id",
     *      referencedColumnName="id",
     *      nullable=false,
     *      onDelete="CASCADE"
     * )
     */
    private $wonder;
    /**
     * @var ArrayCollection | PlayerAchievement[]
     * @OneToMany(targetEntity="PlayerAchievement", mappedBy="score")
     */
    private $playerAchievements;
    /**
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    private $side;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $playerCount;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $rank;
    /**
     * @var boolean
     * @ORM\Column(type="integer", length=1)
     */
    private $last;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $militaryFive;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $militaryThree;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $militaryOne;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $militaryMinusOne;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $militaryShield;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $militaryScore;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $cashCoins;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $cashMinusOne;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $cashScore;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $wonderStage;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $wonderScore;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $civicScore;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $tradeScore;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $scienceGear;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $scienceTablet;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $scienceCompass;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $scienceScore;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $guildScore;
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $leadersScore;
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $citiesScore;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $totalScore;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $position;

    public function __construct()
    {
        $this->playerAchievements = new ArrayCollection();
    }

    /**
     * @param int $cashCoins
     */
    public function setCashCoins($cashCoins)
    {
        $this->cashCoins = $cashCoins;
    }

    /**
     * @return int
     */
    public function getCashCoins()
    {
        return $this->cashCoins;
    }

    /**
     * @param int $cashMinusOne
     */
    public function setCashMinusOne($cashMinusOne)
    {
        $this->cashMinusOne = $cashMinusOne;
    }

    /**
     * @return int
     */
    public function getCashMinusOne()
    {
        return $this->cashMinusOne;
    }

    /**
     * @return int
     */
    public function getCashScore()
    {
        return $this->cashScore;
    }

    /**
     * @param int $cashScore
     */
    public function setCashScore($cashScore)
    {
        $this->cashScore = $cashScore;
    }

    /**
     * @param int $citiesScore
     */
    public function setCitiesScore($citiesScore)
    {
        $this->citiesScore = $citiesScore;
    }

    /**
     * @return int
     */
    public function getCitiesScore()
    {
        return $this->citiesScore;
    }

    /**
     * @param int $civicScore
     */
    public function setCivicScore($civicScore)
    {
        $this->civicScore = $civicScore;
    }

    /**
     * @return int
     */
    public function getCivicScore()
    {
        return $this->civicScore;
    }

    /**
     * @param Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param int $guildScore
     */
    public function setGuildScore($guildScore)
    {
        $this->guildScore = $guildScore;
    }

    /**
     * @return int
     */
    public function getGuildScore()
    {
        return $this->guildScore;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $last
     */
    public function setLast($last)
    {
        $this->last = $last;
    }

    /**
     * @return boolean
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @param int $leadersScore
     */
    public function setLeadersScore($leadersScore)
    {
        $this->leadersScore = $leadersScore;
    }

    /**
     * @return int
     */
    public function getLeadersScore()
    {
        return $this->leadersScore;
    }

    /**
     * @param int $militaryFive
     */
    public function setMilitaryFive($militaryFive)
    {
        $this->militaryFive = $militaryFive;
    }

    /**
     * @return int
     */
    public function getMilitaryFive()
    {
        return $this->militaryFive;
    }

    /**
     * @param int $militaryShield
     */
    public function setMilitaryShield($militaryShield)
    {
        $this->militaryShield = $militaryShield;
    }

    /**
     * @return int
     */
    public function getMilitaryShield()
    {
        return $this->militaryShield;
    }

    /**
     * @param int $militaryMinusOne
     */
    public function setMilitaryMinusOne($militaryMinusOne)
    {
        $this->militaryMinusOne = $militaryMinusOne;
    }

    /**
     * @return int
     */
    public function getMilitaryMinusOne()
    {
        return $this->militaryMinusOne;
    }

    /**
     * @param int $militaryOne
     */
    public function setMilitaryOne($militaryOne)
    {
        $this->militaryOne = $militaryOne;
    }

    /**
     * @return int
     */
    public function getMilitaryOne()
    {
        return $this->militaryOne;
    }

    /**
     * @param int $militaryScore
     */
    public function setMilitaryScore($militaryScore)
    {
        $this->militaryScore = $militaryScore;
    }

    /**
     * @return int
     */
    public function getMilitaryScore()
    {
        return $this->militaryScore;
    }

    /**
     * @param int $militaryThree
     */
    public function setMilitaryThree($militaryThree)
    {
        $this->militaryThree = $militaryThree;
    }

    /**
     * @return int
     */
    public function getMilitaryThree()
    {
        return $this->militaryThree;
    }

    /**
     * @param int $playerCount
     */
    public function setPlayerCount($playerCount)
    {
        $this->playerCount = $playerCount;
    }

    /**
     * @return int
     */
    public function getPlayerCount()
    {
        return $this->playerCount;
    }

    /**
     * @param Player | int $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * @return int | Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param int $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param int $scienceCompass
     */
    public function setScienceCompass($scienceCompass)
    {
        $this->scienceCompass = $scienceCompass;
    }

    /**
     * @return int
     */
    public function getScienceCompass()
    {
        return $this->scienceCompass;
    }

    /**
     * @param int $scienceGear
     */
    public function setScienceGear($scienceGear)
    {
        $this->scienceGear = $scienceGear;
    }

    /**
     * @return int
     */
    public function getScienceGear()
    {
        return $this->scienceGear;
    }

    /**
     * @param int $scienceScore
     */
    public function setScienceScore($scienceScore)
    {
        $this->scienceScore = $scienceScore;
    }

    /**
     * @return int
     */
    public function getScienceScore()
    {
        return $this->scienceScore;
    }

    /**
     * @param int $scienceTablet
     */
    public function setScienceTablet($scienceTablet)
    {
        $this->scienceTablet = $scienceTablet;
    }

    /**
     * @return int
     */
    public function getScienceTablet()
    {
        return $this->scienceTablet;
    }

    /**
     * @param string $side
     */
    public function setSide($side)
    {
        $this->side = $side;
    }

    /**
     * @return string
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * @param int $totalScore
     */
    public function setTotalScore($totalScore)
    {
        $this->totalScore = $totalScore;
    }

    /**
     * @return int
     */
    public function getTotalScore()
    {
        return $this->totalScore;
    }

    /**
     * @param int $tradeScore
     */
    public function setTradeScore($tradeScore)
    {
        $this->tradeScore = $tradeScore;
    }

    /**
     * @return int
     */
    public function getTradeScore()
    {
        return $this->tradeScore;
    }

    /**
     * @param Wonder $wonder
     */
    public function setWonder($wonder)
    {
        $this->wonder = $wonder;
    }

    /**
     * @return Wonder
     */
    public function getWonder()
    {
        return $this->wonder;
    }

    /**
     * @param int $wonderScore
     */
    public function setWonderScore($wonderScore)
    {
        $this->wonderScore = $wonderScore;
    }

    /**
     * @return int
     */
    public function getWonderScore()
    {
        return $this->wonderScore;
    }

    /**
     * @param int $wonderStage
     */
    public function setWonderStage($wonderStage)
    {
        $this->wonderStage = $wonderStage;
    }

    /**
     * @return int
     */
    public function getWonderStage()
    {
        return $this->wonderStage;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return PlayerAchievement[]|ArrayCollection
     */
    public function getPlayerAchievements()
    {
        return $this->playerAchievements;
    }

    /**
     * @param PlayerAchievement[]|ArrayCollection $playerAchievements
     */
    public function setPlayerAchievements($playerAchievements)
    {
        $this->playerAchievements = $playerAchievements;
    }

    /**
     * @return string
     */
    public function getPlayerInfo()
    {
        return $this->getPlayer()->getName().' - '.$this->getWonder()->getName().': '.$this->getSide();
    }

    public function getPlayedOn()
    {
        return $this->getGame()->getPlayedOn();
    }
}
