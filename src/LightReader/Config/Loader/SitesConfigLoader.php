<?php

namespace LightReader\Config\Loader;

use LightReader\Config\Loader\ConfigLoader;

/**
* Loads sites config files
*/
class SitesConfigLoader extends ConfigLoader
{
    /**
     * @inheritdoc
     */
    function __construct()
    {
        parent::__construct(__DIR__.'/../Cache/sitesConfigMatcher.php', 'sites.yml');
    }

    /**
     * @inheritdoc
     */
    protected function generatePHPCode(array $configValues)
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