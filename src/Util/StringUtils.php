<?php
namespace App\Util;

class StringUtils
{
    /**
     * @param string $string
     * @param bool $lcFirst
     * @return string
     */
    public function camelize($string, $lcFirst = true)
    {
        $result = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        if ($lcFirst) {
            $result = lcFirst($result);
        }
        return $result;

    }
}
