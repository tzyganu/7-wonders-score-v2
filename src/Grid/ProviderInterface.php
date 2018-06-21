<?php
namespace App\Grid;

interface ProviderInterface
{
    /**
     * @return Column[]
     */
    public function getColumns();
}
