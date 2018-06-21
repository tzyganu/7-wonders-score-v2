<?php
namespace App\Achievement\Calculator\General\WinSpecial;

use App\Achievement\Calculator\CalculatorInterface;
use App\Entity\Score;

class Gosu extends Joongsu implements CalculatorInterface
{
    /**
     * @return int
     */
    protected function getWinsLimit()
    {
        return 2 * parent::getWinsLimit();
    }

    /**
     * @param Score $score
     * @return string
     */
    protected function getWinKey(Score $score)
    {
        return $score->getWonder()->getId().'_'.$score->getSide();
    }
}
