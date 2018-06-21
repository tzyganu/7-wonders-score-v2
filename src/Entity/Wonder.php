<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity
 */
class Wonder
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false, length=50)
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true, "default":1})
     */
    private $active;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true, "default":1})
     */
    private $official;
    /**
     * @var WonderSet
     * @ManyToOne(targetEntity="WonderSet", cascade={"all"}, fetch="LAZY", inversedBy="wonders")
     * @ORM\JoinColumn(
     *      name="wonder_set_id",
     *      referencedColumnName="id",
     *      nullable=false,
     *      onDelete="CASCADE"
     * )
     */
    private $wonderSet;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true, "default":1})
     */
    private $playableWithLeaders;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true, "default":1})
     */
    private $playableWithoutLeaders;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true, "default":1})
     */
    private $playableWithCities;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true, "default":1})
     */
    private $playableWithoutCities;
    /**
     * @var ArrayCollection | Score[]
     * @OneToMany(targetEntity="Score", mappedBy="wonder")
     */
    private $scores;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->scores = new ArrayCollection();
    }

    /**
     * @param int $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return (bool)$this->active;
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $playableWithLeaders
     */
    public function setPlayableWithLeaders($playableWithLeaders)
    {
        $this->playableWithLeaders = $playableWithLeaders;
    }

    /**
     * @return int
     */
    public function getPlayableWithLeaders()
    {
        return (bool)$this->playableWithLeaders;
    }

    /**
     * @param WonderSet $wonderSet
     */
    public function setWonderSet($wonderSet)
    {
        $this->wonderSet = $wonderSet;
    }

    /**
     * @return WonderSet
     */
    public function getWonderSet()
    {
        return $this->wonderSet;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $playableWithoutLeaders
     */
    public function setPlayableWithoutLeaders($playableWithoutLeaders)
    {
        $this->playableWithoutLeaders = $playableWithoutLeaders;
    }

    /**
     * @return int
     */
    public function getPlayableWithoutLeaders()
    {
        return (bool)$this->playableWithoutLeaders;
    }

    /**
     * @param int $playableWithCities
     */
    public function setPlayableWithCities($playableWithCities)
    {
        $this->playableWithCities = $playableWithCities;
    }

    /**
     * @return int
     */
    public function getPlayableWithCities()
    {
        return (bool)$this->playableWithCities;
    }

    /**
     * @param int $playableWithoutCities
     */
    public function setPlayableWithoutCities($playableWithoutCities)
    {
        $this->playableWithoutCities = $playableWithoutCities;
    }

    /**
     * @return int
     */
    public function getPlayableWithoutCities()
    {
        return (bool)$this->playableWithoutCities;
    }

    /**
     * @return int
     */
    public function getOfficial()
    {
        return (bool)$this->official;
    }

    /**
     * @param int $official
     */
    public function setOfficial($official)
    {
        $this->official = $official;
    }

    /**
     * @return Score[]|ArrayCollection
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * @return PlayerAchievement[]|ArrayCollection
     */
    public function getPlayerAchievements()
    {
        $scores = $this->getScores();
        $achievements = [];
        foreach ($scores as $score) {
            $scoreAchievements = $score->getPlayerAchievements();
            foreach ($scoreAchievements as $scoreAchievement) {
                $achievements[] = $scoreAchievement;
            }
        }
        return $achievements;
    }
}
