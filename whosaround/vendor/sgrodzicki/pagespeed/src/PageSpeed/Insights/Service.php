<?php

namespace PageSpeed\Insights;

use PageSpeed\Insights\Exception\InvalidArgumentException;
use PageSpeed\Insights\Exception\RuntimeException;

class Service
{
	/**
	 * Google API key
	 *
	 * @var string
	 * @link https://code.google.com/apis/console#access
	 */
	private $key;

	/**
	 * @var string
	 */
	private $gateway = 'https://www.googleapis.com/pagespeedonline/v1';

	/**
	 * @param string $key
	 * @throws Exception\InvalidArgumentException
	 * @return Service
	 */
	public function __construct($key)
	{
		if (39 <> strlen($key)) {
			throw new InvalidArgumentException('Key should be exactly 39 characters long');
		}

		$this->key = $key;

		return $this;
	}

	/**
	 * Returns PageSpeed score, page statistics, and PageSpeed formatted results for specified URL
	 *
	 * @param string $url
	 * @param string $locale
	 * @param string $strategy
	 * @return array
	 * @throws Exception\InvalidArgumentException
	 * @throws Exception\RuntimeException
	 */
	public function getResults($url, $locale = 'en_US', $strategy = 'desktop')
	{
		if (0 === preg_match('#http(s)?://.*#i', $url)) {
			throw new InvalidArgumentException('Invalid URL');
		}

		$client = new \Guzzle\Service\Client($this->gateway);

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->get('runPagespeed');
		$request->getQuery()
			->set('key', $this->key)
			->set('prettyprint', false) // reduce the response payload size
			->set('url', $url)
			->set('locale', $locale)
			->set('strategy', $strategy);

		try {
			$response = $request->send();
			$response = $response->getBody();
			$response = json_decode($response, true);

			return $response;
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
			$response = $e->getResponse();
			$response = $response->getBody();
			$response = json_decode($response);

			throw new RuntimeException($response->error->message, $response->error->code);
		}
	}
}
