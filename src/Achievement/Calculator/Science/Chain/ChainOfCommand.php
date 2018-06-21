<?php
namespace App\Achievement\Calculator\Science\Chain;

use App\Achievement\Calculator\CalculatorInterface;

class ChainOfCommand extends BallAndChain implements CalculatorInterface
{
    const CHAIN_LiMIT = 5;
}
