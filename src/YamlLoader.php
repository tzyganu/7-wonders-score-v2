<?php
namespace App;

use Symfony\Component\Yaml\Yaml;

class YamlLoader
{
    /**
     * @param $file
     * @return mixed
     * @throws \Exception
     */
    public function load($file)
    {
        $values = Yaml::parseFile($file, true);
        if (null === $values) {
            throw new \Exception(sprintf('Could not load file %s', $file));
        }
        return $values;
    }
}
