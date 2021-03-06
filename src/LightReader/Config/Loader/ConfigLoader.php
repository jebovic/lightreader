<?php

namespace LightReader\Config\Loader;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Config\Definition\Processor;
use LightReader\Config\Loader\YamlLoader;
use LightReader\Config\Validator\AppConfigurationValidator;
use LightReader\Config\Validator\SitesConfigurationValidator;

/**
 * Load config files helper
 */
class ConfigLoader
{
    protected $cachePath;
    protected $configDirectories;
    protected $yamlFileName;
    protected $config;
    public    $loadedFromCache = true;

    /**
     * ConfigLoader constructor
     *
     * @param string $cachePath         Full path to cache file
     * @param string $yamlFileName      Yaml config file name
     *
     * @param array  $configDirectories Array of directories where to find some config files
     */
    function __construct($cachePath = false, $yamlFileName = false, $configDirectories = array())
    {
        $this->cachePath         = (!$cachePath) ? __DIR__.'/../Cache/configMatcher.php' : $cachePath;
        $this->yamlFileName      = (!$yamlFileName) ? 'app.yml' : $yamlFileName;
        $this->configDirectories = empty($configDirectories) ? array(__DIR__.'/..') : $configDirectories;
        $configMatcherCache      = new ConfigCache($this->cachePath, true);

        if (!$configMatcherCache->isFresh()) {
            $this->loadedFromCache = false;
            $this->writeCache($configMatcherCache);
        }

        $this->config = require $this->cachePath;
    }

    /**
     * Get sites loaded from configuration
     *
     * @return array Array of sites configuration
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Write cache file into $this->cachePath
     *
     * @param  ConfigCache $sitesMatcherCache ConfigCache object
     *
     * @return boolean                        true if cache wroten
     */
    protected function writeCache(ConfigCache $matcherCache)
    {
        $locator         = new FileLocator($this->configDirectories);
        $yamlConfigFiles = $locator->locate($this->yamlFileName, null, false);
        $resources       = array();
        $configValues    = array();

        // Load config from Yaml config files
        foreach ($yamlConfigFiles as $yamlConfigFile) {
            $loaderResolver   = new LoaderResolver(array(new YamlLoader($locator)));
            $delegatingLoader = new DelegatingLoader($loaderResolver);
            $configValues[]   = $delegatingLoader->load($yamlConfigFile);
            $resources[]      = new FileResource($yamlConfigFile);
        }

        // Process config validation
        $baseFileName = basename($this->yamlFileName, ".yml");
        $processor    = new Processor();
        switch ($baseFileName) {
            case 'app':
                $configuration = new AppConfigurationValidator();
                break;
            case 'sites':
                $configuration = new SitesConfigurationValidator();
                break;
        }
        $processedConfiguration = $processor->processConfiguration(
            $configuration,
            $configValues);

        // Genrerate and write cache content
        $cacheContent = $this->generatePHPCode($processedConfiguration);
        $matcherCache->write($cacheContent, $resources);
        return true;
    }

    /**
     * Generate php code to store in cache file
     *
     * @param  array $configValues Array of all config files
     *
     * @return string Content to write in cache file
     */
    protected function generatePHPCode(array $configValues)
    {
        $code = '<?php  return array(';
        foreach ($configValues as $paramName => $paramValue) {
            if ( is_array( $paramValue ) )
            {
                $code .= $this->generatePHPArray($paramName, $paramValue);
            }
            else
            {
                $code .= $this->generatePHPLine($paramName, $paramValue);
            }
        }
        $code .= ');';
        return $code;
    }

    /**
     * Generate a hash
     *
     * @param string $paramName  Parameter name
     * @param mixed  $paramValue Parameter value
     *
     * @return string Generated hash
     */
    protected function generatePHPArray($paramName, $paramValue)
    {
        $code = sprintf('"%1s" => array(', $paramName);
        foreach ($paramValue as $subParamName => $subParamValue)
        {
            if ( is_array($subParamValue) )
            {
                $code .= $this->generatePHPArray($subParamName, $subParamValue);
            }
            else
            {
                $code .= $this->generatePHPLine($subParamName, $subParamValue);
            }
        }
        $code .= '),';
        return $code;
    }

    /**
     * Generate a hash line for an array
     *
     * @param string $paramName  Parameter name
     * @param mixed  $paramValue Parameter value
     *
     * @return string Generated line for hash
     */
    protected function generatePHPLine( $paramName, $paramValue )
    {
        if (is_bool($paramValue))
        {
            $paramValue = ($paramValue) ? "true" : "false";
            return sprintf('"%1s" => %2s,', $paramName, $paramValue);
        }
        return sprintf('"%1s" => "%2s",', $paramName, $paramValue);
    }
}