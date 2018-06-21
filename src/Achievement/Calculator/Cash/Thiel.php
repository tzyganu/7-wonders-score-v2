<?php
namespace App\Achievement\Calculator\Cash;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Game;
use App\Entity\Score;

class Thiel extends Trump implements CalculatorInterface
{
    const LIMIT = 30;
}