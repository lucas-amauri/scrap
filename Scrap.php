<?php

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

	public function addCookie($cookie) {
		$this->cookies[] = $cookie;
	}

	public function setPostHeaders($post_headers) {
		$this->post_headers = $post_headers;
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
		curl_setopt ($chOcr, CURLOPT_USERAGENT, $this->user_agent); 
		curl_setopt($chOcr, CURLOPT_TIMEOUT, 10);
		if ($this->headers) {
			curl_setopt($chOcr, CURLOPT_HTTPHEADER, $this->post_headers);
		}
		
		if ($this->cookies) {
			curl_setopt($chOcr, CURLOPT_COOKIE, implode(';', $this->cookies));
		}

		if ($method == 'POST') {			
			curl_setopt($chOcr, CURLOPT_POST, 1);
			curl_setopt($chOcr, CURLOPT_POSTFIELDS, $params);
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

		return $query;
	}
}