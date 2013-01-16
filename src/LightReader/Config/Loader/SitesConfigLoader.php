<?php

namespace LightReader\Config\Loader;

use LightReader\Config\Loader\ConfigLoader;

/**
* Loads sites config files
*/
class SitesConfigLoader extends ConfigLoader
{
    /**
     * @inheritdoc
     */
    function __construct()
    {
        parent::__construct(__DIR__.'/../Cache/sitesConfigMatcher.php', 'sites.yml');
    }
}