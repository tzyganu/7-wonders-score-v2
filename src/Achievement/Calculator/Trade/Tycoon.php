<?php
namespace App\Achievement\Calculator\Trade;

use App\Achievement\Calculator\CalculatorInterface;

class Tycoon extends Trader implements CalculatorInterface
{
    const SCORE_LIMIT = 20;
}
