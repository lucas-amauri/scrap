<?php

namespace Scrap;

/**
 * Handle HTTP request for scrap methods
 * @author: Lucas Amauri Oliveira
 */
class Scrap {
	/**
	 * Request timeout
	 * @var int
	 */
	private $timeout = 10;

	/**
	 * SSL Version
	 * @var string
	 */
	private $SSL_VERSION = 1;

	/**
	 * HTTP Request cookies
	 * @var array
	 */
	private $cookies = array();

	/**
	 * Curl resource info
	 * @var array
	 */
	private $curl_info;

	/**
	 * HTTP User Agent
	 * @var string
	 */
	private $user_agent = "Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36";

	/**
	 * HTTP Request Headers
	 * @var string
	 */
	private $headers;

	/**
	 * Define if request use Follow location option
	 * @var boolean
	 */
	private $follow_location = true;

	/**
	 * Define HTTP Proxy host
	 * @var string
	 */
	private $proxy_host;

	/**
	 * HTTP Proxy port
	 * @var int
	 */
	private $proxy_port;

	/**
	 * Define if output response headers
	 * @var boolean
	 */
	private $response_header = false;

	/**
	 * Encoding
	 * @var string
	 */
	private $encoding;

	/**
	 * Request timeout
	 * @var integer
	 */
	private $timeout = 300;

	public function addCookie($cookie) {
		$this->cookies[] = $cookie;
	}

	public function setHeaders($headers) {
		$this->headers = $headers;
	}

	public function setSslVersion($version) {
		$this->SSL_VERSION = $version;
	}

	public function setFollowLocation($follow_location) {
		$this->follow_location = $follow_location;
	}

	public function setUserAgent($user_agent) {
		$this->user_agent = $user_agent;
	}

	public function setResponseHeaders($response_header) {
		$this->response_header = $response_header;
	}

	public function setEncoding($encoding) {
		$this->encoding = $encoding;
	}

	public function setTimeout($timeout) {
		$this->timeout = $timeout;
	}

	public function setProxyHost($proxy_host) {
		$this->proxy_host = $proxy_host;
	}

	public function setProxyPort($proxy_port) {
		$this->proxy_port = $proxy_port;
	}

	/**
	 * Send HTTP request
	 * @param string $url
	 * @param string $method (default: GET)
	 * @param string $params
	 * @return string
	 */
	public function sendRequest($url, $method = 'GET', $params = '') {
		$chOcr = curl_init();

		curl_setopt($chOcr, CURLOPT_URL, $url);		
		curl_setopt($chOcr, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($chOcr, CURLOPT_FOLLOWLOCATION, $this->follow_location);
		curl_setopt($chOcr, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($chOcr, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($chOcr, CURLOPT_SSLVERSION, $this->SSL_VERSION);
		curl_setopt($chOcr, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($chOcr, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt($chOcr, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($chOcr, CURLOPT_USERAGENT, $this->user_agent); 
		curl_setopt($chOcr, CURLOPT_TIMEOUT, 10);
		curl_setopt($chOcr, CURLINFO_HEADER_OUT, true);
		if ($this->response_header) {
			curl_setopt($chOcr, CURLOPT_HEADER, true);
		}
		if ($this->headers) {
			curl_setopt($chOcr, CURLOPT_HTTPHEADER, $this->headers);
		}
		
		if ($this->cookies) {
			curl_setopt($chOcr, CURLOPT_COOKIE, implode(';', $this->cookies));
		}

		if ($method == 'POST') {			
			curl_setopt($chOcr, CURLOPT_POST, 1);
			curl_setopt($chOcr, CURLOPT_POSTFIELDS, $params);
		}

		if ($this->proxy_host) {
			curl_setopt($chOcr, CURLOPT_PROXY, $this->proxy_host);
		}

		if ($this->proxy_port) {
			curl_setopt($chOcr, CURLOPT_PROXYPORT, $this->proxy_port);
		}
		
		$result = curl_exec ($chOcr);
		$this->curl_info = curl_getinfo($chOcr);
		curl_close ($chOcr);

		return (string)$result;
	}

	public function getInfo() {
		return $this->curl_info;
	}

	public function clearCookies() {
		@unlink('cookie.txt');
	}

	/**
	 * Convert an array to url query
	 * @param array $array
	 * @param boolean $question_auto
	 * @return string
	 */
	public function array2query(array $array, $question_auto = true) {
		$query = $question_auto ? '?' : '';

		foreach ($array as $key => $value) {
			$query .= urlencode($key) . '=' . urlencode($value) . '&';
		}

		$query = substr($query, 0, -1);

		return $query;
	}
}