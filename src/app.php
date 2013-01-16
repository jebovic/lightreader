<?php

use Silex\Provider\UrlGeneratorServiceProvider;
use LightReader\Config\Loader\ConfigLoader;

require_once __DIR__.'/../vendor/autoload.php';

// Application initialization
$app = new Silex\Application();
$app['debug'] = true;
$app->register(new UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/LightReader/Views',
    'twig.autoescape' => false,
));

// Load configuration
$sitesLoader     = new ConfigLoader( __DIR__.'/LightReader/Config/Cache/sites.cache.php', 'sites.yml' );
$app['sites']    = $sitesLoader->getConfig();
$appConfigLoader = new ConfigLoader( __DIR__.'/LightReader/Config/Cache/app.cache.php', 'app.yml' );
$app['config']   = $appConfigLoader->getConfig();

// routing definitions
require __DIR__.'/routing.php';

return $app;