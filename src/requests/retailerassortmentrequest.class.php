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

namespace Syndy\Api\Requests;

require_once dirname(__FILE__)."/syndybaserequest.class.php";
require_once dirname(__FILE__)."/../contracts/retailer/retailerassortment.class.php";

use Syndy\Api\Net;
use Syndy\Api\Exceptions;
use Syndy\Api\Utility;
use Syndy\Api\Contracts;

/**
 * The RetailerAssortmentRequest request wraps around the /retailer/assortment API
 * resource and allows for easier parameterization for common use-cases.
 */
class RetailerAssortmentRequest extends SyndyBaseRequest {

	private $offset = null;

	private $amount = null;

	private $fromDate = null;

	public function __construct(Net\SyndyApiConnection &$connection, $fromDate = null) {
		parent::__construct($connection);

		if ($fromDate !== null) {
			$this->setFromDate($fromDate);
		}
	}

	public function setOffset($offset) {
		if (!is_int($offset) || $offset < 0) {
			throw new Exceptions\SyndyApiException("Offset must be an integer greater than or equal to 0");
		}

		$this->offset = $offset;
	}

	public function setAmount($amount) {
		if (!is_int($amount) || $amount < 1) {
			throw new Exceptions\SyndyApiException("Amount must be an integer greater than 0");
		}

		$this->amount = $amount;
	}

	/**
	 * Sets the date value on which to filter the returned result set. Only products in the
	 * assortment that have been updated after the specified date will be included.
	 *
	 * @param $fromDate 		The fromDate as a valid datetime-string or integer value (seconds since UNIX epoch)
	 */
	public function setFromDate($fromDate) {
		if (is_int($fromDate)) {
			$this->fromDate = gmdate("Y-m-d\TH:i:s.00\Z", $fromDate);
			return;
		}

		$time = strtotime($fromDate);
		if ($time === false) {
			throw new Exceptions\SyndyApiException("Invalid from date. Must be parsable by strtotime!");
		}
		$this->fromDate = gmdate("Y-m-d\TH:i:s.00\Z", $time);
	}

	public function getOffset() {
		return $this->offset;
	}

	public function getAmount() {
		return $this->offset;
	}

	public function execute($raw = false) {
		$response = $this->connection->sendRequest("GET", "retailer/assortment", $this->getQueryString());
		return $raw ? $response : new Contracts\Retailer\RetailerAssortment($response);
	}

	/**
	 * Helps construct the query string based on the parameters of this
	 * request.
	 */
	private function getQueryString() {
		$queryString = new Utility\QueryString();

		if ($this->offset !== null) {
			$queryString->set("\$skip", $this->offset);
		}

		if ($this->amount !== null) {
			$queryString->set("\$top", $this->amount);
		}

		if ($this->fromDate !== null) {
			$queryString->set("\$filter", "DateLastUpdate gt DateTime'" . $this->fromDate ."'");
		}

		return $queryString;
	}
}
?>