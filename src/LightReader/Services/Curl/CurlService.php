<?php

namespace LightReader\Services\Curl;

class CurlService
{
	// set to false if no proxy connection needed, else set $proxy = '<ip>:<port>';
	// @TODO : implement proxy auth with user/passwd
	private $proxy = false;
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

		if ( $this->proxy !== false )
		{
			$proxyArr = array(
				CURLOPT_HTTPPROXYTUNNEL => 0,
				CURLOPT_PROXY => '10.100.1.27:8989');
			$this->options = $proxyArr + $this->options;
		}
		$this->ressource = curl_init();
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