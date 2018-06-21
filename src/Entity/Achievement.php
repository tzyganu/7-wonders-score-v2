<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`achievement`")
 */
class Achievement
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="`id`")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false, length=50)
     */
    private $name;
    /**
     * @var Color
     * @ManyToOne(targetEntity="AchievementColor", cascade={"all"}, fetch="LAZY", inversedBy="achievements")
     * @ORM\JoinColumn(
     *      name="achievement_color_id",
     *      referencedColumnName="id",
     *      nullable=false,
     *      onDelete="CASCADE"
     * )
     */
    private $achievementColor;
    /**
     * @var int
     * @ManyToOne(targetEntity="AchievementGroup", cascade={"all"}, fetch="LAZY", inversedBy="achievements")
     * @ORM\JoinColumn(
     *      name="group_id",
     *      referencedColumnName="id",
     *      nullable=false,
     *      onDelete="CASCADE"
     * )
     */
    private $group;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $identifier;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $description;
    /**
     * @var ArrayCollection | PlayerAchievement[]
     * @OneToMany(targetEntity="PlayerAchievement", mappedBy="achievement")
     */
    private $playerAchievements;

    /**
     * Achievement constructor.
     */
    public function __construct()
    {
        $this->playerAchievements = new ArrayCollection();
    }

    /**
     * @param AchievementColor $achievementColor
     */
    public function setAchievementColor($achievementColor)
    {
        $this->achievementColor = $achievementColor;
    }

    /**
     * @return AchievementColor
     */
    public function getAchievementColor()
    {
        return $this->achievementColor;
    }

    /**
     * @param int | AchievementGroup $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return int | AchievementGroup
     */
    public function getGroup()
    {
        return $this->group;
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
     * @return string
     */
    public function getGroupName()
    {
        return $this->getGroup()->getName();
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return PlayerAchievement[]|ArrayCollection
     */
    public function getPlayerAchievements()
    {
        return $this->playerAchievements;
    }
}
