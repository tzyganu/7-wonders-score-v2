<?php
namespace App\Tests\Score\Calculator;

use App\Score\Calculator\Science;
use PHPUnit\Framework\TestCase;

class ScienceTest extends TestCase
{
    /**
     * @covers Science::calculate
     */
    public function testCalculate()
    {
        $science = new Science();
        $this->assertEquals(
            10,
            $science->calculate([
                Science::TABLET => 1,
                Science::GEAR => 1,
                Science::COMPASS => 1,
            ])
        );
        $this->assertEquals(
            13,
            $science->calculate([
                Science::TABLET => 1,
                Science::GEAR => 2,
                Science::COMPASS => 1,
            ])
        );
        $this->assertEquals(
            36,
            $science->calculate([
                Science::TABLET => 6,
            ])
        );
        $this->assertEquals(
            0,
            $science->calculate([])
        );
    }
}