<?php
namespace App;

class Hash
{
    /**
     * @param $string
     * @return string
     */
    public function hash($string)
    {
        return sha1($string);
    }
}
