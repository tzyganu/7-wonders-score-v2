<?php
namespace App\Achievement;

class Progress
{
    private $needed;
    private $done;

    /**
     * @param $done
     * @param $needed
     */
    public function __construct($done, $needed)
    {
        $this->done = $done;
        $this->needed = $needed;
    }

    /**
     * @return mixed
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * @return mixed
     */
    public function getNeeded()
    {
        return $this->needed;
    }

    /**
     * @param int $precision
     * @return int|string
     */
    public function getPercentage($precision = 2)
    {
        if ($this->getNeeded() === 0) {
            return 0;
        }
        $percentage = min($this->getDone() * 100 / $this->getNeeded(), 100);
        return number_format($percentage, $precision);
    }
}
