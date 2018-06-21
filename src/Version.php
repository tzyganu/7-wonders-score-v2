<?php
namespace App;

class Version
{
    const VERSION = '2.0';

    /**
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }
}