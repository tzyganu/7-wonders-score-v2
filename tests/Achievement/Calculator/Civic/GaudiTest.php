<?php
namespace App\Tests\Achievement\Calculator\Civic;

use App\Achievement\Calculator\Civic\Gaudi;
use App\Achievement\Progress;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Score;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GaudiTest extends TestCase
{
    /**
     * @var Score | MockObject
     */
    private $score;
    /**
     * @var Gaudi
     */
    private $gaudi;

    /**
     * setup tests
     */
    protected function setUp()
    {
        /** @var \App\Entity\Score | MockObject $score */
        $this->score = $this->createMock(\App\Entity\Score::class);
        $this->gaudi = new Gaudi();
    }

    /**
     * @covers Gaudi::validate()
     */
    public function testValidateNotValid()
    {
        $this->score->method('getCivicScore')->willReturn(19);
        $this->assertFalse($this->gaudi->validate($this->score));
    }

    /**
     * @covers Gaudi::validate()
     */
    public function testValidateValidAtLimit()
    {
        $this->score->method('getCivicScore')->willReturn(24);
        $this->assertFalse($this->gaudi->validate($this->score));
    }

    /**
     * @covers Gaudi::validate()
     */
    public function testValidateValid()
    {
        $this->score->method('getCivicScore')->willReturn(70);
        $this->assertTrue($this->gaudi->validate($this->score));
    }

    /**
     * @covers Gaudi::getProgress()
     */
    public function testGetProgress()
    {
        /** @var Player | MockObject $player */
        $player = $this->createMock(Player::class);
        /** @var Score | MockObject $score1 */
        $score1 = $this->createMock(Score::class);
        $score1->method('getCivicScore')->willReturn(10);
        /** @var Score | MockObject $score2 */
        $score2 = $this->createMock(Score::class);
        $score2->method('getCivicScore')->willReturn(20);
        $player->method('getScores')->willReturn([$score1, $score2]);
        $expected = new Progress(20, Gaudi::LIMIT);
        $this->assertEquals($expected, $this->gaudi->getProgress($player));
    }
}
