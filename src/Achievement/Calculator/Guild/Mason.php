<?php
namespace App\Achievement\Calculator\Guild;

use App\Achievement\Calculator\CalculatorInterface;

class Mason extends Fishmonger implements CalculatorInterface
{
    const SCORE_LIMIT = 40;
}
