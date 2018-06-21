<?php
namespace App\Achievement\Calculator;

use App\Achievement\Progress;
use App\Entity\Player;
use App\Entity\Score;

interface CalculatorInterface
{
    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score);

    /**
     * @param Player $player
     * @return Progress
     */
    public function getProgress(Player $player);
}
