<?php
namespace App\Grid\Column;

use App\Grid\Column;

class DecimalColumn extends Column
{
    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return number_format($value, 2, '.', '');
    }
}
