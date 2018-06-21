<?php
namespace App\Grid\Column;

use App\Grid\Column;

class IntegerColumn extends Column
{
    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        if ($value === null) {
            return null;
        }
        return (int)$value;
    }
}
