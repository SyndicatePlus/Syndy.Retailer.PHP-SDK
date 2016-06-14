<?php
// Copyright (c) 2016 Syndicate Plus B.V.

// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.

namespace Syndy\Api\Net;

require_once dirname(__FILE__) ."/../utility/querystring.class.php";
require_once dirname(__FILE__) ."/../utility/guid.class.php";
require_once dirname(__FILE__) ."/../auth/syndyapicredentials.class.php";
require_once dirname(__FILE__) ."/../exceptions/authorizationexception.class.php";
require_once dirname(__FILE__) ."/../contracts/authenticationresponse.class.php";
require_once dirname(__FILE__) ."/functions.php";

use Syndy\Api\Utility;
use Syndy\Api\Auth;
use Syndy\Api\Exceptions;
use Syndy\Api\Contracts;

/**
 * The SyndyApiConnection class provides the raw API access mechanism, including
 * authentication. A connection, apart from its authenticat
 */
class SyndyApiConnection
{
	private $cultureId = null;

	private $credentials = null;

	private $apiEndpoint = "https://api.syndy.com";

	public function __construct(Auth\SyndyApiCredentials &$syndyApiCredentials) {
		$this->credentials = $syndyApiCredentials;
	}

	public function setCredentials(Auth\SyndyApiCredentials &$syndyApiCredentials) {
		$this->credentials = $syndyApiCredentials;
	}

	public function getCredentials() {
		return $this->credentials;
	}

	public function setCultureId($cultureId) {
		if (!is_string($cultureId)) {
			throw new Exceptions\SyndyApiException("Culture Id must be provided as a string");
		}

		$this->cultureId = $cultureId;
	}

	public function getCultureId() {
		return $this->cultureId;
	}

	/**
	 * Sends a request to the Syndy API. This method automatically takes care of authorization.
	 * As a result, sending a request through this method may cause more than one actual
	 * HTTP request to be sent if, for example, a resource returns a 401 - Unauthorized status.
	 *
	 * @param $method 			The HTTP method (e.g. GET/POST/PUT/DELETE)
	 * @param $resource 		The resource to request, e.g. "product/{id}"
	 * @param $queryString 		The queryString. Mixed. Can be of type QueryString or a plain string
	 * @return stdClass 		Returns the json_decode'd response
	 */
	public function sendRequest($method, $resource, $queryString = "") {
		// Test validity of the $method parameter
		$method = strtoupper($method);
		if (!in_array($method, array("GET", "POST", "DELETE"))) {
			throw new InvalidArgumentException("Method must be one of GET, POST, or DELETE");
		}

		// Before anything else, find out if we need to authenticate
		if (!$this->credentials->hasToken()
			|| $this->credentials->getToken()->isExpired()) {
			$this->authenticate();
		}

		$url = $this->buildUrl($resource, $queryString);
		$response = file_get_contents($url, false, stream_context_create(array(
			"http" => array(
				"method" => $method,
				"header" => "Authorization: ". $this->credentials->getToken() ."\r\n".
							"Content-Type: application/x-www-form-urlencoded\r\n".
							($this->cultureId != null ? "Accept-Language: ". $this->cultureId ."\r\n" : "")
			)
		)));

		if ( $response === false )
			return $response;

		return json_decode($response);
	}

	/**
	 * Forcefully authenticates with the Syndy API, regardless of whether a valid
	 * token already exists or not.
	 *
	 * Updates existing SyndyApiCredentials member.
	 *
	 * @return Syndy\Api\Auth\AuthenticationToken
	 */
	public function authenticate() {
		$resource = "auth";

		$url = $this->buildUrl($resource);

		$authorizationHeader = $this->generateAuthorizationHeader("GET", $resource, "");

		$response = file_get_contents($url, false, stream_context_create(array(
			"http" => array(
				"ignore_errors" => true,
				"method" => "GET",
				"header" => "Authorization: ". $authorizationHeader ."\r\n".
							"Content-Type: application/x-www-form-urlencoded\r\n"
			)
		)));

		$headers = parseHeaders($http_response_header);
		if ($headers["response_code"] != 200 || $response === false) {
			throw new Exceptions\AuthorizationException("Authentication failed!");
		}

		$parsedResponse = json_decode($response);
		$authenticationResponse = new Contracts\AuthenticationResponse($parsedResponse);

		// Set token on the credentials
		$token = new Auth\AuthenticationToken($authenticationResponse->token, $authenticationResponse->dateExpires);
		$this->credentials->setToken($token);
		return $token;		
	}

	private function generateAuthorizationHeader($method, $resource, $queryString) {
		// Prepare the query string and other values
		$preparedQueryString = $this->prepareQueryString(new Utility\QueryString($queryString));
		$nonce = Utility\Guid::create();
		$timestamp = time();

		// Create signature:
		$endpoint = $this->buildApiEndpointUrl();
		$str = $this->credentials->privateKey . $method . ("/".trim($resource, "/")) . $preparedQueryString . $nonce . $timestamp;
		$signature = base64_encode(hash("sha512", $str, true));

		// Create authorization header
		$header = "Key=\"". $this->credentials->publicKey ."\",Timestamp=\"". $timestamp ."\",Nonce=\"". $nonce ."\",Signature=\"". $signature ."\"";
		return $header;
	}

	/**
	 * Builds a valid url for accessing given resource on the Syndy API
	 * @param $resource 		The resource, e.g. "auth", or "product/{id}"
	 * @param $queryString 		The query string parameters. Accepts a string as well as a QueryString object.
	 * @return string 			The requested API url.
	 */
	private function buildUrl($resource, $queryString = null) {
		return $this->buildApiEndpointUrl() 
			. "/"
			. trim($resource, "/") 
			. ($queryString == null || strlen($queryString) == 0 ? "" : "?".trim($queryString, "?"));
	}

	/**
	 * Turns a QueryString object into an acceptable string representation for
	 * use in signature creation. Sorts alphabetically by keys, and URL encodes
	 * (RFC 3986) both keys and values.
	 * 
	 * @access private
	 * @param $queryString 		The QueryString object
	 * @return string 			The prepared query string
	 */
	private function prepareQueryString(Utility\QueryString $queryString) {
		$queryString = iterator_to_array($queryString);
		ksort($queryString, SORT_STRING);
		foreach ( $queryString as $key => $value ) {
			unset($queryString[$key]);
			$key = $this->urlencode($key);
			$value = $this->urlencode($value);
			$queryString[$key] = $value;
		}
		return implode("&", array_map(function($key, $value) { return $key."=".$value; }, array_keys($queryString), array_values($queryString)));
	}

	/**
	 * RFC 3986 fixes for PHP's included rawurlencode function
	 */
	private function urlencode($value) {
		$value = rawurlencode($value); $pos = 0;
		while ( ($pos = strpos($value, "%", $pos)) !== false ) {
			// check if it matches a urlencoded value
			$encodedChar = substr($value, $pos, 3);
			if ( preg_match("/^%[0-9A-F]{2}$/", $encodedChar) ) {
				$value = substr_replace($value, strtolower($encodedChar), $pos, 3);
			}
			$pos += 3;
		}
		return $value;
	}

	/**
	 * Builds the API endpoint with the specified version number.
	 */
	private function buildApiEndpointUrl() {
		// TODO: Reintroduce this when we have versioning for the new API.
		// if ( strpos($this->apiEndpoint, "/v".$this->version) === false ) 
		// 	return trim($this->apiEndpoint, "/") . "/v". $this->version;
		return trim($this->apiEndpoint, "/");
	}
}