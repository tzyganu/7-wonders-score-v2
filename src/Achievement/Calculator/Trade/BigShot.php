<?php
namespace App\Achievement\Calculator\Trade;

use App\Achievement\Calculator\CalculatorInterface;

class BigShot extends Trader implements CalculatorInterface
{
    const SCORE_LIMIT = 13;
}
