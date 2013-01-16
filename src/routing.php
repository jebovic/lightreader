<?php

use LightReader\Services\Sites\SiteService;

$menu_links = array();

$app->get('/', function () use ($app) {
    $result = array(
        'content' => array('hello you !'),
        'links' => array(),
        'page' => array(
            'title' => 'Home Page',
            'active' => 'index' )
        );
    return $app['twig']->render('content.html.twig', $result);
})
->bind('index');
$menu_links[] = array( 'index', 'Home Page');

foreach ($app['sites'] as $routeName => $siteParams)
{
    $route = sprintf('/%s/{page}', $routeName);
    $app->get($route, function ($page) use ($app, $routeName, $siteParams) {
        $siteService = new SiteService( $app, $page, $siteParams);
        $pageInfos = array(
            'title' => sprintf('%1s - #%2s', $siteParams['title'], $page),
            'active' => $routeName );
        $result = $siteService->displayLatest() + array( 'page' => $pageInfos );
        return $app['twig']->render('content.html.twig', $result);
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
        $app->get($randomRouteName, function () use ($app, $randomRouteName, $siteParams) {
            $siteService = new SiteService($app, null, $siteParams);
            $pageInfos = array(
                'title' => $siteParams['title'] . ' Random',
                'active' => $randomRouteName );
            $result = $siteService->displayRandom() + array( 'page' => $pageInfos );
            return $app['twig']->render('content.html.twig', $result);
        })
        ->bind($randomRouteName);
        $menu_links[] = array( $randomRouteName, $siteParams['shortTitle'] . ' Random');
    }
}

$app['menu_links'] = $menu_links;