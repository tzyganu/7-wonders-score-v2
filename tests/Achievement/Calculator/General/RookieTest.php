<?php
namespace App\Tests\Achievement\Calculator\General;

use App\Achievement\Calculator\General\Played\Rookie;
use App\Achievement\Progress;
use App\Entity\Player;
use App\Entity\Score;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RookieTest extends TestCase
{
    /**
     * @var Score | MockObject
     */
    private $score;
    /**
     * @var Player | MockObject
     */
    private $player;
    /**
     * @var ArrayCollection | MockObject
     */
    private $scores;
    /**
     * @var Rookie
     */
    private $rookie;

    /**
     * setup tests
     */
    protected function setUp()
    {
        /** @var \App\Entity\Game | MockObject $game */
        $this->game = $this->createMock(\App\Entity\Game::class);
        /** @var \App\Entity\Score | MockObject $score */
        $this->score = $this->createMock(\App\Entity\Score::class);
        $this->player = $this->createMock(\App\Entity\Player::class);
        $this->score->method('getPlayer')->willReturn($this->player);
        $this->scores = $this->createMock(ArrayCollection::class);
        $this->player->method('getScores')->willReturn($this->scores);
        $this->rookie = new Rookie();
    }

    /**
     * @covers Rookie::validate()
     */
    public function testValidateNotValid()
    {
        $this->scores->method('count')->willReturn(4);
        $this->assertFalse($this->rookie->validate($this->score));
    }

    /**
     * @covers Rookie::validate()
     */
    public function testValidateValidAtLimit()
    {
        $this->scores->method('count')->willReturn(5);
        $this->assertTrue($this->rookie->validate($this->score));
    }

    /**
     * @covers Rookie::validate()
     */
    public function testValidateValid()
    {
        $this->scores->method('count')->willReturn(100);
        $this->assertTrue($this->rookie->validate($this->score));
    }

    /**
     * @covers Gaudi::getProgress()
     */
    public function testGetProgress()
    {
        /** @var Player | MockObject $player */
        $player = $this->createMock(Player::class);
        /** @var ArrayCollection | MockObject $scores$e1 */
        $scores = $this->createMock(ArrayCollection::class);
        $scores->method('count')->willReturn(4);
        $player->method('getScores')->willReturn($scores);
        $expected = new Progress(4, Rookie::LIMIT);
        $this->assertEquals($expected, $this->rookie->getProgress($player));
    }
}
