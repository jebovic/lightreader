<?php

namespace LightReader\Services\Sites;

use LightReader\Services\Curl\CurlService;
use Symfony\Component\DomCrawler\Crawler;

abstract class SiteService
{
    public function displayLatest()
    {
        $url = $this->url . sprintf( $this->urlPage, $this->pageFormat );
        $curl = new CurlService();
        $curl = $curl->setOptions( array( CURLOPT_URL => $url ) );
        $grab = $curl->grab();
        $curl->close();
	    $content = $this->formatRaw( $grab, $this->grabSelector );

	    $links = array();
	    if ( $this->page > 1 )
	    {
		    $links[] = array(
                'href' => $this->app['url_generator']->generate($this->latestName, array( 'page' => $this->page-1 )),
                'name' => 'précédent',
                'id'   => 'prev'
	    	);
	    }
	    $links[] = array(
            'href' => $this->app['url_generator']->generate($this->latestName, array( 'page' => $this->page+1 )),
            'name' => 'suivant',
            'id'   => 'next'
	    	);

	    return array(
	    	'content' => $content,
	    	'links' => $links);
    }

    public function displayRandom()
    {
        $url = $this->url . $this->urlRandom;
        $curl = new CurlService();
        $curl = $curl->setOptions( array( CURLOPT_URL => $url ) );
        $grab = $curl->grab();
        $curl->close();
	    $content = $this->formatRaw( $grab, $this->grabSelector );

		$links = array();
	    $links[] = array(
	    	'href' => $this->app['url_generator']->generate($this->randomName, array()),
	    	'name' => 'random'
	    	);

	    return array(
	    	'content' => $content,
	    	'links' => $links);
    }

    protected function formatRaw( $raw, $selector )
    {
    	$crawler = new Crawler();
	    $crawler->addHtmlContent( $raw );
	    $allowedTags = $this->allowedTags;
	    $items = $crawler->filter( $selector )->each(function ($node, $i) use( $allowedTags )
	    {
	        return strip_tags($node->ownerDocument->saveXML($node), $allowedTags);
	    });

	    return $items;
    }
}