<?php

use LightReader\Services\Sites\SiteService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$ext = $app['config']['design'];
$menu_links = array();

$app->get('/', function (Request $Request) use ($app, $ext) {
    $response = new Response();
    $response = $response->prepare($Request);
    $result = array(
        'page' => array(
            'title' => 'Home Page',
            'active' => 'index' )
        );
    return $response->setContent( $app['twig']->render('html/index.html.twig', $result) );
})
->bind('index');
$menu_links[] = array( 'index', 'Home Page');

foreach ($app['sites'] as $routeName => $siteParams)
{
    $route = sprintf('/%s/{page}', $routeName);
    $app->get($route, function (Request $Request, $page) use ($app, $routeName, $siteParams, $ext) {
        if ( $page == 0 ) $page++;
        $response = new Response();
        $response = $response->prepare($Request);
        $siteService = new SiteService( $app, $page, $siteParams);
        $pageInfos = array(
            'title' => sprintf('%1s - #%2s', $siteParams['title'], $page),
            'active' => $routeName,
            'site_active' => $routeName );
        $result = $siteService->displayLatest() + array( 'page' => $pageInfos );
        return $response->setContent( $app['twig']->render(sprintf('%s/content.%s.twig', $ext, $ext), $result) );
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
        $app->get($randomRouteName, function (Request $Request) use ($app, $routeName, $randomRouteName, $siteParams, $ext) {
            $response = new Response();
            $response = $response->prepare($Request);
            $siteService = new SiteService($app, null, $siteParams);
            $pageInfos = array(
                'title' => $siteParams['title'] . ' Random',
                'active' => $randomRouteName,
                'site_active' => $routeName );
            $result = $siteService->displayRandom() + array( 'page' => $pageInfos );
            return $response->setContent( $app['twig']->render(sprintf('%s/content.%s.twig', $ext, $ext), $result) );
        })
        ->bind($randomRouteName);
        $menu_links[] = array( $randomRouteName, $siteParams['shortTitle'] . ' Random');
    }
}

$app['menu_links'] = $menu_links;