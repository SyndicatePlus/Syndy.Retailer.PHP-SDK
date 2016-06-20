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

namespace Syndy\Api\Auth;

require_once dirname(__FILE__) ."/authenticationtoken.class.php";
/**
 * Because a connection cannot always be expected to hold state,
 * the SyndyApiCredentials class is an object that can be shared
 * across multiple API connections without requiring those
 * connections to re-authenticate every time. The aim of this
 * class is also to be easily serializable so it can be persisted
 * across sessions.
 */
class SyndyApiCredentials
{
	public function __construct($publicKey, $privateKey, AuthenticationToken $token = null) {
		if (!is_string($publicKey)) {
			throw new InvalidArgumentException("The publicKey argument must be of type string");
		}

		if (!is_string($privateKey)) {
			throw new InvalidArgumentException("The privateKey argument must be of type string");
		}

		if ($token != null && !($token instanceof AuthenticationToken)) {
			throw new InvalidArgumentException("The token argument, if supplied, must be of type Syndy\\Api\\Auth\\AuthenticationToken");
		}

		$this->publicKey = $publicKey;
		$this->privateKey = $privateKey;
		$this->token = $token;
	}

	private $token = null;

	public $publicKey = null;

	public $privateKey = null;

	public function setToken(AuthenticationToken &$token) {
		$this->token = $token;
	}

	public function getToken() {
		return $this->token;
	}

	public function hasToken() {
		return $this->token !== null;
	}

	public function expireToken() {
		$this->token = null;
	}
}
?>