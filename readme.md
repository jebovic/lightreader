LightReader Readme
=================

LightReader has been developed just for fun. It's not a real application with cool features, it is just a base idea with few lines of code and good way to discover the great [Silex](http://silex.sensiolabs.org "Silex") PHP Micro-Framework.

What is it ?
------------
LightReader is a light weight application that allows you to read some funny sites content @work.
There is no style, no images, just content.
This application has been build with [Silex](http://silex.sensiolabs.org "Silex") PHP Micro-Framework.

For the time being, you can read content from these sites :
* DansTonChat (paginated list of quotes and random quotes)
* VieDeMerde (paginated list of quotes and random quotes)
* EntenduAuBoulot (paginated list of quotes and random quotes)
* PersonalBranling (paginated list of posts and random posts)

Install
-------
LightReader is delivered without vendors. So you have to [install composer](http://getcomposer.org/ "Install composer") to install all dependencies.

Webserver configuration
-----------------------
Please refer to the [silex documentation](http://silex.sensiolabs.org/doc/web_servers.html "Webserver configuration") to set up your webserver.
If you prefer to use a VirtualHost setup with apache, you'll find an example into the conf/ directory.

How to
------
You will find the whole application code into the src/ directory.
With very small efforts you should be able to add some pretty features to this application.
Here are some examples...

### Add sites
Adding a site is quite simple. Just add a service into src/LightReader/Services/Sites/ directory. For example :

```php
<?php

namespace LightReader\Services\Sites;

use LightReader\Services\Curl\CurlService;
use Symfony\Component\DomCrawler\Crawler;
use LightReader\Services\Sites\SiteService;

class ExampleService extends SiteService
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
		$this->url          = 'http://example.com';
		$this->urlPage      = '/latest/%d.html';
		$this->urlRandom    = '/random.html';
		$this->page         = $page;
		$this->pageFormat   = $page;
		$this->grabSelector = '.item-content';
		$this->allowedTags  = '<br><span>';
		$this->app          = $app;
		$this->latestName   = 'example';
		$this->randomName   = 'example_random';
	}
}
```

Then you have to define a new route into the src/LightReader/routing.php
```php
$app->get('/example/latest/{page}', function ($page) use ($app) {
    $dtc = new ExampleService( $app, $page );
    $pageInfos = array(
        'title' => sprintf('Example page title - #%s', $page),
        'active' => 'example' );
    $result = $dtc->displayLatest( (int) $page ) + array( 'page' => $pageInfos );
    return $app['twig']->render('content.html.twig', $result);
})
->assert('page', '\d+')
->value('page', 1)
->bind('example');
$menu_links[] = array( 'example', 'DTC');

$app->get('/example/random', function () use ($app) {
    $dtc = new ExampleService( $app );
    $pageInfos = array(
        'title' => 'Random quotes from your example site',
        'active' => 'example_random' );
    $result = $dtc->displayRandom() + array( 'page' => $pageInfos );
    return $app['twig']->render('content.html.twig', $result);
})
->bind('example_random');
$menu_links[] = array( 'example_random', 'Your example site Random');
```
The menu links will be generated automaticaly