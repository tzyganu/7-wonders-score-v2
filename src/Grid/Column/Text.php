<?php
namespace App\Grid\Column;

use App\Grid\Column;

class Text extends Column
{
    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return (string)$value;
    }
}
