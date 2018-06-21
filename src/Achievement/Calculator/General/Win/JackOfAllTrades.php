<?php
namespace App\Achievement\Calculator\General\Win;

use App\Achievement\Calculator\CalculatorInterface;
use App\Entity\Wonder;
use Doctrine\Common\Persistence\ManagerRegistry;

class JackOfAllTrades extends Wonderer implements CalculatorInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * JackOfAllTrades constructor.
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
        return count($this->managerRegistry->getRepository(Wonder::class)->findBy(['official' => 1]));
    }
}
