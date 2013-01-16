<?php

use Silex\Provider\UrlGeneratorServiceProvider;
use LightReader\Config\Loader\SitesLoader;


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
$sitesLoader  = new SitesLoader();
$app['sites'] = $sitesLoader->getSitesLoaded();

// routing definitions
require __DIR__.'/routing.php';

return $app;