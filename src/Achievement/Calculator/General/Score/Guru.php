<?php
namespace App\Achievement\Calculator\General\Score;

use App\Achievement\Calculator\CalculatorInterface;

class Guru extends Specialist implements CalculatorInterface
{
    const ZERO_SCORE_LIMIT = 3;
}
