<?php
namespace App\Achievement\Calculator\General\Score;

use App\Achievement\Calculator\CalculatorInterface;
use App\Achievement\Progress;
use App\Entity\Category;
use App\Entity\Player;
use App\Entity\Score;
use App\Util\StringUtils;
use Doctrine\Common\Persistence\ManagerRegistry;

class Specialist implements CalculatorInterface
{
    const ZERO_SCORE_LIMIT = 2;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var StringUtils
     */
    private $stringUtils;

    /**
     * Specialist constructor.
     * @param ManagerRegistry $managerRegistry
     * @param StringUtils $stringUtils
     */
    public function __construct(ManagerRegistry $managerRegistry, StringUtils $stringUtils)
    {
        $this->managerRegistry = $managerRegistry;
        $this->stringUtils = $stringUtils;
    }

    /**
     * @param Score $score
     * @return bool
     */
    public function validate(Score $score)
    {
        if ($score->getRank() != 1) {
            return false;
        }
        return $this->compareZeroScores($this->getZeroScores($score));
    }

    /**
     * @param Player $player
     * @return Progress
     */
    public function getProgress(Player $player)
    {
        $max = 0;
        foreach ($player->getScores() as $score) {
            $zeroScores = $this->getZeroScores($score);
            if ($zeroScores > $max) {
                $max = $zeroScores;
            }
        }
        return new Progress($max, static::ZERO_SCORE_LIMIT);
    }

    /**
     * @param Score $score
     * @return bool|int
     */
    protected function getZeroScores(Score $score)
    {
        $categories = $this->getCategories();
        $zeroScores = 0;
        foreach($categories as $category) {
            if ($category->getOptional()) {
                $getter = $this->stringUtils->camelize('get_'.$category->getCode());
                if (!$score->getGame()->$getter()) {
                    continue;
                }
            }
            $method = $this->stringUtils->camelize('get_'.$category->getCode().'_score');
            if ($score->$method() <= 0) {
                $zeroScores++;
            }
        }
        return $zeroScores;
    }

    /**
     * @param $zeroScores
     * @return bool
     */
    protected function compareZeroScores($zeroScores)
    {
        return $zeroScores >= static::ZERO_SCORE_LIMIT;
    }

    /**
     * @return Category[]
     */
    protected function getCategories()
    {
        return $this->managerRegistry->getRepository(Category::class)->findAll();
    }

}
