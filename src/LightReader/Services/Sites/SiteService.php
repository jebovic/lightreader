<?php

namespace LightReader\Services\Sites;

use LightReader\Services\Curl\CurlService;
use Symfony\Component\DomCrawler\Crawler;

class SiteService
{
    private $siteParams;
    private $page;
    private $pageFormat;
    private $app;
    private $appConfig;

    function __construct( \Silex\Application $app, $page = null, array $siteParams )
    {
        $this->siteParams = $siteParams;
        $this->page       = $page;
        $this->pageFormat = sprintf($siteParams['pageFormat'], $this->page);
        $this->app        = $app;
        $this->appConfig  = $app['config'];
    }

    public function displayLatest()
    {
        // @TODO : Find a best way to allow pagination format in config file
        eval('$pageFormat = '.$this->pageFormat. ';');
        $url  = $this->siteParams['url'] . sprintf( $this->siteParams['urlPage'], $pageFormat );
        $curl = new CurlService();
        $curl = $curl
                    ->setProxy( $this->appConfig['proxy'] )
                    ->setOptions( array( CURLOPT_URL => $url ) );
        $grab = $curl->grab();
        $curl->close();
        $content = $this->formatRaw( $grab, $this->siteParams['grabSelector'] );

        $links = array();
        if ( $this->page > 1 )
        {
            $links[] = array(
                'href' => $this->app['url_generator']->generate($this->siteParams['routeName'], array( 'page' => $this->page-1 )),
                'name' => $this->appConfig['prevLink'],
                'id'   => 'prev'
            );
        }
        $links[] = array(
            'href' => $this->app['url_generator']->generate($this->siteParams['routeName'], array( 'page' => $this->page+1 )),
            'name' => $this->appConfig['nextLink'],
            'id'   => 'next'
            );

        return array(
            'content' => $content,
            'links' => $links);
    }

    public function displayRandom()
    {
        $url = $this->siteParams['url'] . $this->siteParams['urlRandom'];
        $curl = new CurlService();
        $curl = $curl
                    ->setProxy( $this->appConfig['proxy'] )
                    ->setOptions( array( CURLOPT_URL => $url ) );
        $grab = $curl->grab();
        $curl->close();
        $content = $this->formatRaw( $grab, $this->siteParams['grabSelector'] );

        $links = array();
        $links[] = array(
            'href' => $this->app['url_generator']->generate($this->siteParams['randomRouteName'], array()),
            'name' => $this->appConfig['randomLink'],
            'id'   => 'random'
            );

        return array(
            'content' => $content,
            'links' => $links);
    }

    protected function formatRaw( $raw, $selector )
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent( $raw );
        $allowedTags = $this->siteParams['allowedTags'];
        $items = $crawler->filter( $selector )->each(function ($node, $i) use( $allowedTags )
        {
            return strip_tags($node->ownerDocument->saveXML($node), $allowedTags);
        });

        return $items;
    }
}