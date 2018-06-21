<?php
namespace App\Tests\Game;

use App\Entity\Score;
use App\Game\Ranker;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use App\Entity\Game;

class RankerTest extends TestCase
{
    /**
     * @covers Ranker::rankScores()
     */
    public function testRankScores()
    {
        $rank1 = new Score();
        $rank1->setTotalScore(10);
        $rank1->setCashCoins(10);

        $rank2 = new Score();
        $rank2->setTotalScore(7);
        $rank2->setCashCoins(10);

        $rank3 = new Score();
        $rank3->setTotalScore(7);
        $rank3->setCashCoins(8);

        $rank4 = new Score();
        $rank4->setTotalScore(4);
        $rank4->setCashCoins(8);

        $rank4Two = new Score();
        $rank4Two->setTotalScore(4);
        $rank4Two->setCashCoins(8);

        $scores = new ArrayCollection();
        $scores->add($rank3);
        $scores->add($rank1);
        $scores->add($rank4);
        $scores->add($rank4Two);
        $scores->add($rank2);

        $ranker = new Ranker();
        $game = $this->createMock(Game::class);
        $game->method('getScores')->willReturn($scores);
        $ranker->rankScores($game);

        $this->assertEquals(1, $rank1->getRank());
        $this->assertEquals(2, $rank2->getRank());
        $this->assertEquals(3, $rank3->getRank());
        $this->assertEquals(4, $rank4->getRank());
        $this->assertEquals(4, $rank4Two->getRank());
        $this->assertFalse($rank1->getLast());
        $this->assertFalse($rank2->getLast());
        $this->assertFalse($rank3->getLast());
        $this->assertTrue($rank4->getLast());
        $this->assertTrue($rank4Two->getLast());
    }
}
