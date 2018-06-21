<?php
namespace App\Achievement\Calculator\Guild;

use App\Achievement\Calculator\CalculatorInterface;

class Blacksmith extends Fishmonger implements CalculatorInterface
{
    const SCORE_LIMIT = 30;
}
