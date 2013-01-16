<?php

namespace LightReader\Config\Loader;

use LightReader\Config\Loader\ConfigLoader;

/**
* Loads sites config files
*/
class AppConfigLoader extends ConfigLoader
{
    function __construct()
    {
        parent::__construct(__DIR__.'/../Cache/appConfigMatcher.php', 'app.yml');
    }
}