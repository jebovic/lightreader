<?php

use LightReader\Services\Sites\DtcService;
use LightReader\Services\Sites\VdmService;
use LightReader\Services\Sites\EabService;
use LightReader\Services\Sites\PbService;

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

$app->get('/dtc/latest/{page}', function ($page) use ($app) {
    $dtc = new DtcService( $app, $page );
    $pageInfos = array(
        'title' => sprintf('Dernières quotes DTC - #%s', $page),
        'active' => 'dtc' );
    $result = $dtc->displayLatest( (int) $page ) + array( 'page' => $pageInfos );
    return $app['twig']->render('content.html.twig', $result);
})
->assert('page', '\d+')
->value('page', 1)
->bind('dtc');
$menu_links[] = array( 'dtc', 'DTC');

$app->get('/dtc/random', function () use ($app) {
    $dtc = new DtcService( $app );
    $pageInfos = array(
        'title' => 'Random quotes DTC',
        'active' => 'dtc' );
    $result = $dtc->displayRandom() + array( 'page' => $pageInfos );
    return $app['twig']->render('content.html.twig', $result);
})
->bind('dtc_random');
$menu_links[] = array( 'dtc_random', 'DTC Random');

$app->get('/vdm/latest/{page}', function ($page) use ($app) {
    $vdm = new VdmService( $app, $page  );
    $pageInfos = array(
        'title' => sprintf('Dernières quotes VDM - #%s', $page),
        'active' => 'vdm' );
    $result = $vdm->displayLatest( (int) $page ) + array( 'page' => $pageInfos );
    return $app['twig']->render('content.html.twig', $result);
})
->assert('page', '\d+')
->value('page', 1)
->bind('vdm');
$menu_links[] = array( 'vdm', 'VDM');

$app->get('/vdm/random', function () use ($app) {
    $vdm = new VdmService( $app );
    $pageInfos = array(
        'title' => 'Random VDM',
        'active' => 'vdm_random' );
    $result = $vdm->displayRandom() + array( 'page' => $pageInfos );
    return $app['twig']->render('content.html.twig', $result);
})
->bind('vdm_random');
$menu_links[] = array( 'vdm_random', 'VDM Random');

$app->get('/eab/latest/{page}', function ($page) use ($app) {
    $eab = new EabService( $app, $page  );
    $pageInfos = array(
        'title' => sprintf('Dernières quotes EAB - #%s', $page),
        'active' => 'eab' );
    $result = $eab->displayLatest( (int) $page ) + array( 'page' => $pageInfos );
    return $app['twig']->render('content.html.twig', $result);
})
->assert('page', '\d+')
->value('page', 1)
->bind('eab');
$menu_links[] = array( 'eab', 'Entendu au boulot');

$app->get('/eab/random', function () use ($app) {
    $eab = new EabService( $app );
    $pageInfos = array(
        'title' => 'Random EAB',
        'active' => 'eab_random' );
    $result = $eab->displayRandom() + array( 'page' => $pageInfos );
    return $app['twig']->render('content.html.twig', $result);
})
->bind('eab_random');
$menu_links[] = array( 'eab_random', 'Entendu au boulot Random');

$app->get('/pb/latest/{page}', function ($page) use ($app) {
    $vmd = new PbService( $app, $page  );
    $pageInfos = array(
        'title' => sprintf('Dernières PB - #%s', $page),
        'active' => 'pb' );
    $result = $vmd->displayLatest( (int) $page ) + array( 'page' => $pageInfos );
    return $app['twig']->render('content.html.twig', $result);
})
->assert('page', '\d+')
->value('page', 1)
->bind('pb');
$menu_links[] = array( 'pb', 'Personal branling');

$app->get('/pb/random', function () use ($app) {
    $vmd = new PbService( $app );
    $pageInfos = array(
        'title' => 'Random PB',
        'active' => 'pb_random' );
    $result = $vmd->displayRandom() + array( 'page' => $pageInfos );
    return $app['twig']->render('content.html.twig', $result);
})
->bind('pb_random');
$menu_links[] = array( 'pb_random', 'Personal branling Random');

$app['menu_links'] = $menu_links;