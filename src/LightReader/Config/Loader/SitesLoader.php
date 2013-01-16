<?php

namespace LightReader\Config\Loader;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\FileResource;
use LightReader\Config\Loader\YamlSitesLoader;

/**
* Loads sites config files
*/
class SitesLoader
{
    private $cachePath;
    private $configDirectories;
    private $yamlFileName;
    private $sitesLoaded;

    /**
     * SitesLoader constructor
     */
    function __construct($configDirectories = array(), $yamlFileName = '')
    {
        $this->cachePath         = __DIR__.'/../Cache/appSitesMatcher.php';
        $this->configDirectories = empty($configDirectories) ? array(__DIR__.'/..') : $configDirectories;
        $this->yamlFileName      = ($yamlFileName == '') ? 'sites.yml' : $yamlFileName;
        $sitesMatcherCache       = new ConfigCache($this->cachePath, true);

        if (!$sitesMatcherCache->isFresh()) {
            $this->writeCache($sitesMatcherCache);
        }

        $this->sitesLoaded = require $this->cachePath;
    }

    /**
     * Get sites loaded from configuration
     *
     * @return array Array of sites configuration
     */
    public function getSitesLoaded() {
        return $this->sitesLoaded;
    }

    /**
     * Write cache file into $this->cachePath
     * @param  ConfigCache $sitesMatcherCache ConfigCache object
     * @return boolean                        true if cache wroten
     */
    private function writeCache(ConfigCache $sitesMatcherCache)
    {
        $locator       = new FileLocator($this->configDirectories);
        $yamlSiteFiles = $locator->locate($this->yamlFileName, null, false);
        $resources     = array();
        $configValues  = array();

        foreach ($yamlSiteFiles as $yamlSiteFile) {
            $loaderResolver   = new LoaderResolver(array(new YamlSitesLoader($locator)));
            $delegatingLoader = new DelegatingLoader($loaderResolver);
            $configValues[]   = $delegatingLoader->load($yamlSiteFile);
            $resources[]      = new FileResource($yamlSiteFile);
        }
        $cacheContent = $this->generatePHPCode($configValues);

        $sitesMatcherCache->write($cacheContent, $resources);
        return true;
    }

    /**
     * Generate php code to store in cache file
     * @param  array  $configValues Array of all config files
     * @return string               Content to write in cache file
     */
    private function generatePHPCode(array $configValues)
    {
        $code = '<?php  return array(';
        foreach ($configValues as $configValue) {
            foreach ($configValue as $siteName => $siteParams) {
                $code .= sprintf('"%1s" => array(', $siteName);
                foreach ($siteParams as $paramName => $paramValue) {
                    $code .= sprintf('"%1s" => "%2s",', $paramName, $paramValue);
                }
                $code .= '),';
            }
        }
        $code .= ');';
        return $code;
    }
}