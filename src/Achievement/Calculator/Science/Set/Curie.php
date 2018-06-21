<?php
namespace App\Achievement\Calculator\Science\Set;

use App\Achievement\Calculator\CalculatorInterface;
use App\Entity\Score;

class Curie extends Newton implements CalculatorInterface
{
    const SET_LIMIT = 3;
}
