<?php
namespace App\Achievement\Calculator\General\WinSpecial;

use App\Achievement\Calculator\CalculatorInterface;
use App\Entity\Wonder;
use Doctrine\Common\Persistence\ManagerRegistry;

class Joongsu extends Chobo implements CalculatorInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * Joongsu constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @return int
     */
    protected function getWinsLimit()
    {
        return count($this->managerRegistry->getRepository(Wonder::class)->findBy(['official' => 0]));
    }
}
