<?php
namespace App\Achievement\Calculator\Wonder;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Score;

class Burj extends Floreasca implements CalculatorInterface
{
    const STAGE_LIMIT = 163;
}
