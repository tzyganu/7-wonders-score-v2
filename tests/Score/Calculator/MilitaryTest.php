<?php
namespace App\Tests\Score\Calculator;

use App\Score\Calculator\Military;
use PHPUnit\Framework\TestCase;

class MilitaryTest extends TestCase
{
    /**
     * @covers Military::calculate
     */
    public function testCalculate()
    {
        $military = new Military();
        $this->assertEquals(
            18,
            $military->calculate([
                Military::FIVE => 2,
                Military::THREE => 2,
                Military::ONE => 2,
                Military::MINUS_ONE => 0
            ])
        );
        $this->assertEquals(
            -6,
            $military->calculate([
                Military::FIVE => 0,
                Military::THREE => 0,
                Military::ONE => 0,
                Military::MINUS_ONE => 6
            ])
        );
        $this->assertEquals(
            2,
            $military->calculate([
                Military::FIVE => 1,
                Military::MINUS_ONE => 3
            ])
        );
        $this->assertEquals(
            0,
            $military->calculate([
                'dummy' => 'dummy'
            ])
        );
    }
}