<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Game
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var
     * @ORM\Column(type="datetime")
     */
    private $playedOn;
    /**
     * @var User
     * @ManyToOne(targetEntity="User", fetch="LAZY")
     * @ORM\JoinColumn(
     *      name="user_id",
     *      referencedColumnName="id",
     *      nullable=false,
     *      onDelete="CASCADE"
     * )
     */
    private $user;
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true, "default":0})
     */
    private $leaders;
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true, "default":1})
     */
    private $cities;
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true, "default":1})
     */
    private $playLeft;
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true, "default":0})
     */
    private $canExclude;
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true})
     */
    private $playerCount;
    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="Score", mappedBy="game", cascade={"persist"})
     */
    private $scores;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->playedOn = new \DateTime();
        $this->scores = new ArrayCollection();
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
     * @param mixed $playedOn
     */
    public function setPlayedOn($playedOn)
    {
        $this->playedOn = $playedOn;
    }

    /**
     * @return mixed
     */
    public function getPlayedOn()
    {
        return $this->playedOn;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $scores
     */
    public function setScores($scores)
    {
        $this->scores = $scores;
    }

    /**
     * @param Score $score
     */
    public function addScore(Score $score)
    {
        $this->scores->add($score);
    }
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection | Score[]
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * @return int
     */
    public function getLeaders()
    {
        return (bool)$this->leaders;
    }

    /**
     * @param int $leaders
     */
    public function setLeaders($leaders)
    {
        $this->leaders = $leaders;
    }

    /**
     * @return int
     */
    public function getCities()
    {
        return (bool)$this->cities;
    }

    /**
     * @param int $cities
     */
    public function setCities($cities)
    {
        $this->cities = $cities;
    }

    /**
     * @return int
     */
    public function getPlayerCount()
    {
        return $this->playerCount;
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
    public function getPlayLeft()
    {
        return $this->playLeft;
    }

    /**
     * @param int $playLeft
     */
    public function setPlayLeft($playLeft)
    {
        $this->playLeft = $playLeft;
    }

    /**
     * @return int
     */
    public function getCanExclude()
    {
        return $this->canExclude;
    }

    /**
     * @param int $canExclude
     */
    public function setCanExclude($canExclude)
    {
        $this->canExclude = $canExclude;
    }

    /**
     * @return int|string
     */
    public function getScoreAverage()
    {
        $sum = 0;
        $count = 0;
        foreach ($this->getScores() as $score) {
            $sum += $score->getTotalScore();
            $count++;
        }
        return ($count === 0) ? 0 : number_format($sum/$count, 2);
    }

    public function getWinnerName()
    {
        $winners = [];
        foreach ($this->getScores() as $score) {
            if ($score->getRank() == 1) {
                $winners[] = $score->getPlayer()->getName();
            }
        }
        return implode(', ', $winners);
    }
}
