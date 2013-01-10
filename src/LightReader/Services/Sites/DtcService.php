<?php

namespace LightReader\Services\Sites;

use LightReader\Services\Curl\CurlService;
use Symfony\Component\DomCrawler\Crawler;
use LightReader\Services\Sites\SiteService;

class DtcService extends SiteService
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
		$this->url          = 'http://danstonchat.com';
		$this->urlPage      = '/latest/%d.html';
		$this->urlRandom    = '/random.html';
		$this->page         = $page;
		$this->pageFormat   = $page;
		$this->grabSelector = '.item-content';
		$this->allowedTags  = '<br><span>';
		$this->app          = $app;
		$this->latestName   = 'dtc';
		$this->randomName   = 'dtc_random';
	}
}