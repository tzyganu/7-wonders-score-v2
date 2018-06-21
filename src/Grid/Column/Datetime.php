<?php
namespace App\Grid\Column;

use App\Grid\Column;

class Datetime extends Column
{
    /**
     * @param \DateTime $value
     * @return string
     */
    public function formatValue($value)
    {
        return $value->format('Y-m-d');
    }
}
