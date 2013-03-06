<?php

namespace LightReader\Services\Sites;

use LightReader\Services\Curl\CurlService;
use Symfony\Component\DomCrawler\Crawler;

/**
 * SiteService class, launch Curl request and return content and metadatas
 */
class SiteService
{
    private $siteParams;
    private $page;
    private $app;
    private $appConfig;

    /**
     * SiteService constructor
     *
     * @param \Silex\Application $app        Silex Application
     * @param int                $page       The page number
     * @param array              $siteParams Site parameters from sites.yml file
     */
    function __construct( \Silex\Application $app, $page = null, array $siteParams )
    {
        $this->siteParams = $siteParams;
        $this->page       = (int) $page;
        $this->app        = $app;
        $this->appConfig  = $app['config'];
    }

    /**
     * Display latest content (default list)
     *
     * @return array Hash with content to display and page infos
     */
    public function displayLatest()
    {
        $pageNumber = (int) $this->siteParams['urlStep'] * ( $this->page - 1 + (int) $this->siteParams['urlFirstPage'] );
        $url  = $this->siteParams['url'] . sprintf( $this->siteParams['urlPattern'], $pageNumber );
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

    /**
     * Display a random list of content
     *
     * @return array Hash with content to display and page infos
     */
    public function displayRandom()
    {
        $url = $this->siteParams['url'] . $this->siteParams['urlRandom'] . '?ts=' . time();
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

    /**
     * Helper for formating content grabed with Curl
     *
     * @param mixed  $raw      Curl request result
     * @param string $selector CSS-like selector to grab only the wanted content
     *
     * @return string Content to display
     */
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