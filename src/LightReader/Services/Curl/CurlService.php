<?php

namespace LightReader\Services\Curl;

/**
 * Curl helper and shortcuts, fluid interface
 */
class CurlService
{
    private $options;
    private $ressource;

    /**
     * Options getter
     *
     * @return array An array of options
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * Options setter
     *
     * @param array $options An array of options
     */
    public function setOptions($options) {
        $this->options = $options + $this->options;

        return $this;
    }

    /**
     * Options initialization, curl_setopt
     *
     * @return CurlService CurlService instance
     */
    private function initOptions()
    {
        foreach ($this->options as $optName => $optValue )
        {
            curl_setopt( $this->ressource, $optName, $optValue );
        }
        return $this;
    }

    /**
     * CurlService constructor
     *
     * @return CurlService CurlService instance
     */
    public function __construct()
    {
        $this->options = array(
            CURLOPT_REFERER => "http://google.fr",
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11'
            );
        $this->ressource = curl_init();
        return $this;
    }

    /**
     * Set proxy options for Curl
     *
     * @param array $proxyConfig Proxy options (url, port, user, password)
     */
    public function setProxy( array $proxyConfig = array() )
    {
        if ( !empty($proxyConfig) && $proxyConfig['url'] != false )
        {
            $proxyUrl   = $proxyConfig['url'];
            $proxyUrl  .= ($proxyConfig['port']) ? ':' . $proxyConfig['port'] : '';
            $proxyAuth  = ($proxyConfig['user']) ? $proxyConfig['user'] : '';
            $proxyAuth .= ($proxyConfig['password']) ? ':' . $proxyConfig['password'] : '';

            $proxyArr = array(
                CURLOPT_HTTPPROXYTUNNEL => 0,
                CURLOPT_PROXY => $proxyUrl);
            if ( $proxyAuth != '' )
            {
                $proxyArr = array(CURLOPT_PROXYUSERPWD => $proxyAuth) + $proxyArr;
            }
            $this->setOptions($proxyArr);
        }
        return $this;
    }

    /**
     * Close Curl connection
     *
     * @return boolean true
     */
    public function close()
    {
        curl_close( $this->ressource );
        return true;
    }

    /**
     * Execute curl request
     *
     * @return Curl request result
     */
    public function grab()
    {
        $this->initOptions();
        return curl_exec( $this->ressource );
    }
}