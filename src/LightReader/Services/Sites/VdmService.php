<?php

namespace LightReader\Services\Sites;

use LightReader\Services\Curl\CurlService;
use Symfony\Component\DomCrawler\Crawler;
use LightReader\Services\Sites\SiteService;

class VdmService extends SiteService
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
		$this->url          = 'http://www.viedemerde.fr';
		$this->urlPage      = '/?page=%d';
		$this->urlRandom    = '/aleatoire';
		$this->page         = $page;
		$this->pageFormat   = $page-1;
		$this->grabSelector = '.article > p';
		$this->allowedTags  = '';
		$this->app          = $app;
		$this->latestName   = 'vdm';
		$this->randomName   = 'vdm_random';
	}
}