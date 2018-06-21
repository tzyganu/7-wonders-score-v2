<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity
 */
class Player
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
     * @ORM\Column(type="integer", nullable=true, options={"unsigned":true, "default":1})
     */
    private $active;

    /**
     * @var ArrayCollection | Score[]
     * @OneToMany(targetEntity="Score", mappedBy="player")
     */
    private $scores;
    /**
     * @var ArrayCollection | PlayerAchievement[]
     * @OneToMany(targetEntity="PlayerAchievement", mappedBy="player")
     */
    private $playerAchievements;
    /**
     * @var int[]
     */
    private $achievementIds;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->playerAchievements = new ArrayCollection();
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
        return $this->playerAchievements;
    }

    /**
     * @return array|\int[]|null
     */
    public function getAchievementIds()
    {
        if ($this->achievementIds === null) {
            $this->achievementIds = [];
            $alreadyAchieved = $this->getPlayerAchievements();
            if ($alreadyAchieved !== null) {
                foreach ($this->getPlayerAchievements() as $playerAchievement) {
                    $this->achievementIds[] = $playerAchievement->getAchievement()->getId();
                }
            }
        }
        return $this->achievementIds;
    }
}
