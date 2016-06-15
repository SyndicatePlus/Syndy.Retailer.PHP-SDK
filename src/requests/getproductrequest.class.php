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
require_once dirname(__FILE__)."/../exceptions/syndyapiexception.class.php";
require_once dirname(__FILE__)."/../contracts/product/product.class.php";

use Syndy\Api\Net;
use Syndy\Api\Exceptions;
use Syndy\Api\Contracts;

class GetProductRequest extends SyndyBaseRequest {

	private $productId = null;

	private $cultureId = null;

	public function __construct(Net\SyndyApiConnection &$connection, $productId) {
		parent::__construct($connection);

		$this->setProductId($productId);
	}

	public function setProductId($productId) {
		if (!$this->validateId($productId)){
			throw new Exceptions\SyndyApiException("Invalid product identifier specified!");
		}

		$this->productId = $productId;
	}

	public function execute() {
		if ($this->productId === null) {
			throw new Exceptions\SyndyApiException("Invalid product identifier specified!");
		}

		$response = $this->connection->sendRequest("GET", "product/".$this->productId);
		return new Contracts\Product\Product($response);
	}

	private function validateId($productId) {
		if (preg_match("/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i", $productId)) {
			return true;
		}
		if (preg_match("/^\d{8}$/", $productId)) {
			return true;
		}
		if (preg_match("/^\d{12}$/", $productId)) {
			return true;
		}
		if (preg_match("/^\d{13}$/", $productId)) {
			return true;
		}
		if (preg_match("/^\d{14}$/", $productId)) {
			return true;
		}
		return false;
	}
} 

?>