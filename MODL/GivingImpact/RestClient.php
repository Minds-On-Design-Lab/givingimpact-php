<?php

namespace MODL\GivingImpact;
use MODL\GivingImpact\Exception as GIException;

class RestClient {

	public $url = false;
	public $headers = array();
	public $port = false;
	public $user_agent = 'MODL Client';

	private $container = false;

	public function __construct($c) {
		$this->container = $c;

	}

	public function buildURL($path, $args = array()) {
		if( !$this->url ) {
			throw new GIException('URL is not defined');
			return;
		}

		$query = '';
		if( count($args) ) {
			$query = '?'.http_build_query($args);
		}
		return sprintf(
			'%s/%s%s', rtrim($this->url, '/'), rtrim($path, '/'), $query
		);
	}

	public function get($data = false) {

		if( $data ) {
			$this->url = $this->buildURL('', $data);
		}

		$raw_json = $this->curlFetch();
		$data = json_decode($raw_json);

		if( $data->error ) {
			throw new GIException($data->message);
			return;
		}

		return $data;

	}

	public function post($data) {
		$raw_json = $this->curlFetch($data);

		$return = json_decode($raw_json);

		if( $return->error ) {
			throw new GIException($return->message);
			return;
		}

		return $return;
	}

	public function curlFetch($data = false) {
		if( $data ) {
			$data = json_encode($data);
		}

		$url = $this->url;

		$ch = curl_init();

		if( strpos($url, ':') !== false ) {
			// we have a port;
			preg_match('/(\:([0-9]*))/', $url, $matches);
			$port = $matches[2];

			$url = str_replace($matches[1], '', $url);
			curl_setopt($ch, CURLOPT_PORT, $port);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);

		if( count($this->headers) ) {
			curl_setopt(
				$ch,
				CURLOPT_HTTPHEADER,
				$this->headers
			);
		}

		if( $data ) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}

		$data = curl_exec($ch);

		curl_close($ch);

		return $data;
	}

}