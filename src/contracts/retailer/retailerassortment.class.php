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

namespace Syndy\Api\Contracts\Retailer;

require_once dirname(__FILE__)."/../basecontract.class.php";
require_once dirname(__FILE__)."/retailerproductreference.class.php";

use Syndy\Api\Contracts;

class RetailerAssortment extends Contracts\BaseContract implements \Iterator {

	protected $totalCount;

	protected $offset;

	protected $resultCount;

	protected $results = array();

	public function __construct($rawData) {
		$this->parse($rawData);
	}

	protected function parse($rawData) {
		$rawData = parent::parse($rawData);

		$this->totalCount = $rawData->TotalCount;
		$this->offset = $rawData->Offset;
		$this->resultCount = $rawData->ResultCount;

		foreach ($rawData->Results as $result) {
			$this->results[] = new Contracts\Retailer\RetailerProductReference($result);
		}
	}

	public function getTotalCount() {
		return $this->totalCount;
	}

	public function getOffset() {
		return $this->offset;
	}

	public function getResultCount() {
		return $this->resultCount;
	}

	public function getResults() {
		return $this->results;
	}

	///////////////////////// Iterator Implementation \\\\\\\\\\\\\\\\\\\\\\\\\\\

	public function current() {
		return current($this->results);
	}

	public function key() {
		return key($this->results);
	}

	public function next() {
		return next($this->results);
	}

	public function rewind() {
		return reset($this->results);
	}

	public function valid() {
		return $this->current() !== false;
	}
}

?>