<?php
namespace App\Grid\Column;

use App\Grid\Column;

class YesNo extends Column
{
    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return $value == 1 ? 'Yes' : 'No';
    }
}
