<?php
namespace App\Achievement\Calculator\Cash;

use App\Achievement\Calculator\CalculatorInterface;

class Gates extends Trump implements CalculatorInterface
{
    const LIMIT = 60;
}