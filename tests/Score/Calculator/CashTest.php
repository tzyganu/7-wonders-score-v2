<?php
namespace App\Tests\Score\Calculator;

use App\Score\Calculator\Cash;
use PHPUnit\Framework\TestCase;

class CashTest extends TestCase
{
    /**
     * @covers Cash::calculate
     */
    public function testCalculate()
    {
        $cash = new Cash();
        $this->assertEquals(
            10,
            $cash->calculate([
                Cash::COINS => 31,
                Cash::MINUS_ONE => 0,
            ])
        );
        $this->assertEquals(
            4,
            $cash->calculate([
                Cash::COINS => 26,
                Cash::MINUS_ONE => 4,
            ])
        );
        $this->assertEquals(
            5,
            $cash->calculate([
                Cash::COINS => 15,
            ])
        );
        $this->assertEquals(
            -2,
            $cash->calculate([
                Cash::MINUS_ONE => 2,
            ])
        );
        $this->assertEquals(
            0,
            $cash->calculate([])
        );
    }
}
