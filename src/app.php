<?php

use LightReader\Config\Loader\ConfigLoader;
use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;

require_once __DIR__.'/../vendor/autoload.php';

// Application initialization
$app          = new Application();
$app['debug'] = false;

$app->register(new UrlGeneratorServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/LightReader/Views',
    'twig.autoescape' => false,
));

$app->register(new HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => __DIR__.'/../cache/',
    'http_cache.options'   => array(
        'allow_reload'     => true,
        'allow_revalidate' => true
)));

// Load configuration
$sitesLoader     = new ConfigLoader( __DIR__.'/LightReader/Config/Cache/sites.cache.php', 'sites.yml' );
$app['sites']    = $sitesLoader->getConfig();
$appConfigLoader = new ConfigLoader( __DIR__.'/LightReader/Config/Cache/app.cache.php', 'app.yml' );
$app['config']   = $appConfigLoader->getConfig();

// routing definitions
require __DIR__.'/routing.php';

return $app;