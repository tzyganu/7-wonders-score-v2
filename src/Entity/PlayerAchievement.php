<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity
 * @Table(name="player_achievement",
 *    uniqueConstraints={
 *        @UniqueConstraint(name="unique_player_achievement",
 *            columns={"player_id", "achievement_id"})
 *    }
 * )
 */
class PlayerAchievement
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var Score
     * @ManyToOne(targetEntity="Score", cascade={"all"}, fetch="LAZY", inversedBy="playerAchievements")
     * @ORM\JoinColumn(
     *      name="score_id",
     *      referencedColumnName="id",
     *      nullable=false,
     *      onDelete="CASCADE"
     * )
     */
    private $score;
    /**
     * @var Player
     * @ManyToOne(targetEntity="Player", fetch="LAZY", inversedBy="playerAchievements")
     * @ORM\JoinColumn(
     *      name="player_id",
     *      referencedColumnName="id",
     *      nullable=false,
     *      onDelete="CASCADE"
     * )
     */
    private $player;
    /**
     * @var Achievement
     * @ManyToOne(targetEntity="Achievement", fetch="LAZY", inversedBy="playerAchievements")
     * @ORM\JoinColumn(
     *      name="achievement_id",
     *      referencedColumnName="id",
     *      nullable=false,
     *      onDelete="CASCADE"
     * )
     */
    private $achievement;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $additional;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Score
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param Score $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * @return Achievement
     */
    public function getAchievement()
    {
        return $this->achievement;
    }

    /**
     * @param Achievement $achievement
     */
    public function setAchievement($achievement)
    {
        $this->achievement = $achievement;
    }

    /**
     * @return string
     */
    public function getAdditional()
    {
        return $this->additional;
    }

    /**
     * @param string $additional
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;
    }
}
