<?php
namespace App\Tests\Achievement\Calculator\Cash;

use App\Achievement\Calculator\Cash\Trump;
use App\Achievement\Progress;
use App\Entity\Player;
use App\Entity\Score;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TrumpTest extends TestCase
{
    /**
     * @var Score | MockObject
     */
    private $score;
    /**
     * @var Trump
     */
    private $trump;

    /**
     * setup tests
     */
    protected function setUp()
    {
        /** @var \App\Entity\Score | MockObject $score */
        $this->score = $this->createMock(Score::class);
        $this->trump = new Trump();
    }

    /**
     * @covers Trump::validate()
     */
    public function testValidateNotValid()
    {
        $this->score->method('getCashCoins')->willReturn(19);
        $this->assertFalse($this->trump->validate($this->score));
    }

    /**
     * @covers Trump::validate()
     */
    public function testValidateValidAtLimit()
    {
        $this->score->method('getCashCoins')->willReturn(21);
        $this->assertTrue($this->trump->validate($this->score));
    }

    /**
     * @covers Trump::validate()
     */
    public function testValidateValid()
    {
        $this->score->method('getCashCoins')->willReturn(70);
        $this->assertTrue($this->trump->validate($this->score));
    }

    /**
     * @covers Trump::getProgress()
     */
    public function testGetProgress()
    {
        /** @var Player | MockObject $player */
        $player = $this->createMock(Player::class);
        /** @var Score | MockObject $score1 */
        $score1 = $this->createMock(Score::class);
        $score1->method('getCashCoins')->willReturn(10);
        /** @var Score | MockObject $score2 */
        $score2 = $this->createMock(Score::class);
        $score2->method('getCashCoins')->willReturn(20);
        $player->method('getScores')->willReturn([$score1, $score2]);
        $expected = new Progress(20, Trump::LIMIT);
        $this->assertEquals($expected, $this->trump->getProgress($player));
    }
}
