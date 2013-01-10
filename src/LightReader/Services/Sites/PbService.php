<?php

namespace LightReader\Services\Sites;

use LightReader\Services\Curl\CurlService;
use Symfony\Component\DomCrawler\Crawler;
use LightReader\Services\Sites\SiteService;

class PbService extends SiteService
{
	protected $url;
	protected $urlPage;
	protected $urlRandom;
	protected $page;
	protected $pageFormat;
	protected $grabSelector;
	protected $allowedTags;
	protected $app;
	protected $latestName;
	protected $randomName;

	function __construct( $app, $page = null )
	{
		$this->url          = 'http://personalbranling.tumblr.com';
		$this->urlPage      = '/page/%d';
		$this->urlRandom    = '/random';
		$this->page         = $page;
		$this->pageFormat   = $page;
		$this->grabSelector = '.post-content';
		$this->allowedTags  = '<img><div>';
		$this->app          = $app;
		$this->latestName   = 'pb';
		$this->randomName   = 'pb_random';
	}
}