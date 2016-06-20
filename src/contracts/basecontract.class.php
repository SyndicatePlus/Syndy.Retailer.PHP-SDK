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

namespace Syndy\Api\Contracts;

require_once dirname(__FILE__)."/../exceptions/syndyapiexception.class.php";

use Syndy\Api\Exceptions;

class BaseContract {

	public function __construct($rawData = null) {
		if ($rawData !== null) {
			$this->parse($rawData);
		}		
	}

	protected function parse($rawData) {
		if (is_string($rawData)) {
			$rawData = json_decode($rawData);
			if ($rawData === false) {
				throw new Exceptions\SyndyApiException("Could not parse json");
			}
		}
		if (!is_object($rawData)) {
			throw new Exceptions\SyndyApiException("Expect raw data to be an object");
		}

		return $rawData;
	}

	public function __get($field) {
		if (!isset($this->$field)) {
			return null;
		}

		return $this->$field;
	}
}
?>