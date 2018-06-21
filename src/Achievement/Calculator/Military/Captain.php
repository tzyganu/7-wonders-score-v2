<?php
namespace App\Achievement\Calculator\Military;

use App\Achievement\Calculator\CalculatorInterface;

class Captain extends Soldier implements CalculatorInterface
{
    const GAME_LIMIT = 5;
}
