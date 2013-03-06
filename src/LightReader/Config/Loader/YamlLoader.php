<?php

namespace LightReader\Config\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * File loader for YAML formated files
 */
class YamlLoader extends FileLoader
{
    /**
     * File parser
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     *
     * @return array  The YAML converted to a PHP array
     */
    public function load($resource, $type = null)
    {
        return Yaml::parse($resource);
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}