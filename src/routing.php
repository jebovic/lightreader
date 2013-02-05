<?php

use LightReader\Services\Sites\SiteService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$ext          = $app['config']['design'];
$menu_links   = array();
$cacheMaxAge  = $app['config']['cache']['maxage'];
// $etagMD5      = md5(json_encode($app['config']));
$cacheExpires = new DateTime();

$cacheExpires->modify( sprintf('+%d seconds', $cacheMaxAge));

$app->get('/', function (Request $Request) use ($app, $ext, $cacheMaxAge, $cacheExpires) {
    $response = new Response();
    $response = $response->prepare($Request);
    // $response->setETag($etagMD5);
    $response->setMaxAge($cacheMaxAge);
    $response->setSharedMaxAge($cacheMaxAge);
    $response->setExpires($cacheExpires);
    $response->setPublic();
    $response->headers->addCacheControlDirective('must-revalidate', true);
    if ($response->isNotModified($Request)) {
        // return the 304 Response immediately
        return $response;
    }
    else
    {
        $result = array(
            'page' => array(
                'title' => 'Home Page',
                'active' => 'index' )
            );
        return $response->setContent( $app['twig']->render(sprintf('%s/index.html.twig', $ext), $result) );
    }
})
->bind('index');
$menu_links[] = array( 'index', 'Home Page');

foreach ($app['sites'] as $routeName => $siteParams)
{
    $route = sprintf('/%s/{page}/{onlyContent}', $routeName);
    $app->get($route, function (Request $Request, $page, $onlyContent) use ($app, $routeName, $siteParams, $ext, $cacheMaxAge, $cacheExpires) {
        if ( $page == 0 ) $page++;
        $response = new Response();
        $response = $response->prepare($Request);
        // $response->setETag($etagMD5);
        $response->setMaxAge($cacheMaxAge);
        $response->setSharedMaxAge($cacheMaxAge);
        $response->setExpires($cacheExpires);
        $response->setPublic();
        $response->headers->addCacheControlDirective('must-revalidate', true);
        if ($response->isNotModified($Request)) {
            // return the 304 Response immediately
            return $response;
        }
        else
        {
            $siteService = new SiteService( $app, $page, $siteParams);
            $pageInfos = array(
                'title' => sprintf('%1s - #%2s', $siteParams['title'], $page),
                'active' => $routeName,
                'site_active' => $routeName,
                'number_next' => $page +1);
            $result = $siteService->displayLatest() + array( 'page' => $pageInfos );
            $tpl = sprintf('%s/onlycontent.html.twig', $ext);
            if ( $onlyContent == 0 )
            {
                $tpl = sprintf('%s/content.html.twig', $ext);
            }
            return $response->setContent( $app['twig']->render($tpl, $result) );
        }
    })
    ->assert('page', '\d+')
    ->value('page', 1)
    ->value('onlyContent', 0)
    ->bind($routeName);
    $menu_links[] = array( $routeName, $siteParams['title']);

    if (
        array_key_exists('randomRouteName', $siteParams) && $siteParams['randomRouteName'] != '' && $siteParams['randomRouteName'] != false
       )
    {
        $randomRouteName = $siteParams['randomRouteName'];
        $route = sprintf('/%s/{onlyContent}', $randomRouteName);
        $app->get($route, function (Request $Request, $onlyContent) use ($app, $routeName, $randomRouteName, $siteParams, $ext) {
            $response = new Response();
            $response = $response->prepare($Request);
            $siteService = new SiteService($app, null, $siteParams);
            $pageInfos = array(
                'title' => $siteParams['title'] . ' Random',
                'active' => $randomRouteName,
                'site_active' => $randomRouteName,
                'number_next' => 0 );
            $result = $siteService->displayRandom() + array( 'page' => $pageInfos );
            $tpl = sprintf('%s/onlycontent.html.twig', $ext);
            if ( $onlyContent == 0 )
            {
                $tpl = sprintf('%s/content.html.twig', $ext);
            }
            return $response->setContent( $app['twig']->render($tpl, $result) );
        })
        ->value('onlyContent', 0)
        ->bind($randomRouteName);
        $menu_links[] = array( $randomRouteName, $siteParams['title'] . ' Random');
    }
}

$app['menu_links'] = $menu_links;