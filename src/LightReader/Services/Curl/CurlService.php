<?php

namespace LightReader\Services\Curl;

class CurlService
{
    private $options;
    private $ressource;

    public function getOptions() {
        return $this->options;
    }

    public function setOptions($options) {
        $this->options = $options + $this->options;

        return $this;
    }

    private function initOptions()
    {
        foreach ($this->options as $optName => $optValue )
        {
            curl_setopt( $this->ressource, $optName, $optValue );
        }
        return $this;
    }

    public function __construct()
    {
        $this->options = array(
            CURLOPT_REFERER => "http://google.fr",
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11'
            );
        $this->ressource = curl_init();
        return $this;
    }

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

    public function close()
    {
        curl_close( $this->ressource );
        return true;
    }

    public function grab()
    {
        $this->initOptions();
        return curl_exec( $this->ressource );
    }
}