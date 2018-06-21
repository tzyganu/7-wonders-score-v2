<?php
namespace App\Achievement\Calculator\Military\Shield;

use App\Achievement\Calculator\CalculatorInterface;

class Turtle extends Hedgehog implements CalculatorInterface
{
    const SHIELD_LIMIT = 500;
}
