﻿LightReader Readme
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
Adding a site is quite simple. Just modify configuration in src/LightReader/Config/sites.yml config file. For example :

```yaml
dtc:                              #Route name, used for routing
    title: 'Dans ton chat'        #Used for page title
    shortTitle: 'DTC'             #Menu link name
    url: 'http://danstonchat.com' #URL to grab
    urlPage: '/latest/%d.html'    #Pagination format, "%d" replaced by page number according to pageFormat parameter
    pageFormat: '%d'              #Used to handle different ways to paginate (page number, offset...) %d is page number
    grabSelector: '.item-content' #CSS selector for content to grab
    allowedTags: '<br><span>'     #All tags are stripped, except for allowedTags parameter
    routeName: 'dtc'              #Main route name for this site, used only for url generator
    randomRouteName: 'dtc_random' #Route name if a random post request is enabled, false if not
    urlRandom: '/random.html'     #Random URL to grab
```
Routes and menu links will be generated automaticaly

Application settings
--------------------
You will find the config file fore LightReader in src/LightReader/Config/app.yml config file :

```yaml
title: 'Light Reader v0'          #Application name, used for page title and home page H1
nextLink: 'Next'                  #Next link name
prevLink: 'Previous'              #Previous link name
randomLink: 'Random'              #Random link name
```

TODO
----
In a very short time :
* Create three design (HTML, XML, and Mobile design)
* Allow connection through proxy with login/pwd authentication
* Find a better way to allow regexp pagination in configuration files