<?php
namespace App\Achievement\Calculator\Leaders;

use App\Achievement\Calculator\CalculatorInterface;

class Caesar extends Churchill implements CalculatorInterface
{
    const SCORE_LIMIT = 25;
}
