<?php

class Scrap {
	private $SSL_VERSION = 1;
	private $cookies = array();
	private $url_start, $url_captcha, $post_url, $curl_info, $model, $headers;

	function __construct() {

	}

	public function addCookie($cookie) {
		$this->cookies[] = $cookie;
	}

	public function addDinamicFields($fields_dinamic) {
		$this->fields_dinamic[] = $fields_dinamic;
	}

	public function setURLInicial($url_start) {
		$this->url_start = $url_start;
	}
	public function setCaptchaURL($url_captcha) {
		$this->url_captcha = $url_captcha;
	}

	public function setPostURL($post_url) {
		$this->post_url = $post_url;
	}

	public function setPostHeaders($post_headers) {
		$this->post_headers = $post_headers;
	}

	public function setSslVersion($version) {
		$this->SSL_VERSION = $version;
	}

	public function setStringValida($string_valid) {
		$this->string_valid = $string_valid;
	}

	public function setModel($model) {
		$this->model = $model;
	}

	public function sendRequest($url, $method = 'GET', $params = '') {
		$chOcr = curl_init();

		curl_setopt($chOcr, CURLOPT_URL, $url);		
		curl_setopt($chOcr, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($chOcr, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($chOcr, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($chOcr, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($chOcr, CURLOPT_SSLVERSION, $this->SSL_VERSION);
		curl_setopt($chOcr, CURLOPT_TIMEOUT, 10);
		curl_setopt($chOcr, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt($chOcr, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt ($chOcr, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36"); 
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

		return $result;
	}

	public function getInfo() {
		return $this->curl_info;
	}

	public function clearCookies() {
		@unlink('cookie.txt');
	}
}