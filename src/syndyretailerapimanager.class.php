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

namespace Syndy\Api;

require_once dirname(__FILE__)."/net/syndyapiconnection.class.php";
require_once dirname(__FILE__)."/requests/retailerassortmentrequest.class.php";
require_once dirname(__FILE__)."/requests/getproductrequest.class.php";

use Syndy\Api\Auth;
use Syndy\Api\Net;
use Syndy\Api\Requests;

class SyndyRetailerApiManager
{
	private $credentials;

	public function __construct(Auth\SyndyApiCredentials &$credentials) {
		$this->credentials = $credentials;
	}

	/**
	 * Creates a new Retailer Assortment request with its own underlying connection object. The
	 * request is returned in un-executed state so the consumer still has the chance to parameterize
	 * the request before executing it.
	 *
	 * @param $fromDate 		The date by which to filter the resultset (include only products with DateLastUpdate >= fromDate)
	 * @param $offset 			Pagination offset
	 * @param $limit 			Pagination limit (num items to include)
	 * @return RetailerAssortmentRequest
	 */
	public function createRetailerAssortmentRequest($fromDate = null, $offset = 0, $limit = 50) {
		$connection = $this->createConnection();

		$request = new Requests\RetailerAssortmentRequest($connection, $fromDate);
		$request->setOffset($offset);
		$request->setAmount($limit);
		return $request;
	}

	/**
	 * Creates a new Get Product request with its own underlyig connection object. The
	 * request is returned in un-executed state so the consumer still has t ehc hance to
	 * parameterize the request before executing it.
	 *
	 * @param $productId		The ProductId for which the lookup should take place
	 * @param $cultureId		The culture id in the context of which the product content should be requested
	 * @return GetProductRequest
	 */
	public function createGetProductRequest($productId, $cultureId) {
		$connection = $this->createConnection();
		$connection->setCultureId($cultureId);

		$request = new Requests\GetProductRequest($connection, $productId);
		return $request;
	}

	private function createConnection() {
		return new Net\SyndyApiConnection($this->credentials);
	}
}
?>