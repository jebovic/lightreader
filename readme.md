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
* Good Reads Quotes (paginated list of quotes)

Install
-------
LightReader is delivered without vendors. So you have to [install composer](http://getcomposer.org/ "Install composer") to install all dependencies.

Set up cache directory :
* create "cache" directory into application root.
* Change rights to your apache user for writing cache files

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
dtc:                                #Route name, used for routing
    title: 'Dans ton chat'          #Used for page title
    shortTitle: 'DTC'               #Menu link name
    url: 'http://danstonchat.com'   #URL to grab
    urlPattern: '/latest/%d.html'   #Pagination format, "%d" replaced by page number according to pageFormat parameter
    urlFirstPage: 1                 #Indicate the list pagination begin to 0 or 1
    urlStep: 1                      #Indicate step : how much do we have to increment when switching from page n to page n+1
    grabSelector: '.item-content'   #CSS selector for content to grab
    allowedTags: '<br><span>'       #All tags are stripped, except for allowedTags parameter
    routeName: 'dtc'                #Main route name for this site, used only for url generator
    randomRouteName: 'dtc_random'   #Route name if a random post request is enabled, delete parameter if not
    urlRandom: '/random.html'       #Random URL to grab, delete parameter if no random post request enabled
```
Routes and menu links will be generated automaticaly

Application settings
--------------------
You will find the config file fore LightReader in src/LightReader/Config/app.yml config file :

```yaml
title: 'Light Reader'               #Name of application, used for page title and home page H1
design: 'mobile'                    #Choose a design, available : html|xml|mobile
nextLink: '&rsaquo;'                #Next link name (unused for mobile)
prevLink: '&lsaquo;'                #Previous link name (unused for mobile)
randomLink: 'Random'                #Random link name (unused for mobile)
proxy:
    url: false                      #Proxy URL, false if not needed
    port: false                     #Proxy port, false if not needed
    user: false                     #Proxy username, false if not needed
    password: false                 #Proxy password, false if not needed
cache:
    maxage: 120                     #Max age and Expires HTTP headers
```