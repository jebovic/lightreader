<?php

use LightReader\Services\Sites\SiteService;

$ext = $app['config']['design'];
$menu_links = array();

$app->get('/', function () use ($app, $ext) {
    $result = array(
        'page' => array(
            'title' => 'Home Page',
            'active' => 'index' )
        );
    return $app['twig']->render(sprintf('index.%s.twig', $ext), $result);
})
->bind('index');
$menu_links[] = array( 'index', 'Home Page');

foreach ($app['sites'] as $routeName => $siteParams)
{
    $route = sprintf('/%s/{page}', $routeName);
    $app->get($route, function ($page) use ($app, $routeName, $siteParams, $ext) {
        $siteService = new SiteService( $app, $page, $siteParams);
        $pageInfos = array(
            'title' => sprintf('%1s - #%2s', $siteParams['title'], $page),
            'active' => $routeName );
        $result = $siteService->displayLatest() + array( 'page' => $pageInfos );
        return $app['twig']->render(sprintf('content.%s.twig', $ext), $result);
    })
    ->assert('page', '\d+')
    ->value('page', 1)
    ->bind($routeName);
    $menu_links[] = array( $routeName, $siteParams['shortTitle']);

    if (
        array_key_exists('randomRouteName', $siteParams) && $siteParams['randomRouteName'] != '' && $siteParams['randomRouteName'] != false
       )
    {
        $randomRouteName = $siteParams['randomRouteName'];
        $app->get($randomRouteName, function () use ($app, $randomRouteName, $siteParams, $ext) {
            $siteService = new SiteService($app, null, $siteParams);
            $pageInfos = array(
                'title' => $siteParams['title'] . ' Random',
                'active' => $randomRouteName );
            $result = $siteService->displayRandom() + array( 'page' => $pageInfos );
            return $app['twig']->render(sprintf('content.%s.twig', $ext), $result);
        })
        ->bind($randomRouteName);
        $menu_links[] = array( $randomRouteName, $siteParams['shortTitle'] . ' Random');
    }
}

$app['menu_links'] = $menu_links;