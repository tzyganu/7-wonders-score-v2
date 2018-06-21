<?php
namespace App\Achievement\Calculator\General\WinSpecial;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Calculator\General\Win\Wonderer;
use App\Entity\Wonder;

class Chobo extends Wonderer implements CalculatorInterface
{
    /**
     * @param Wonder $wonder
     * @return bool
     */
    protected function isValidWonder(Wonder $wonder)
    {
        return !parent::isValidWonder($wonder);
    }

}
