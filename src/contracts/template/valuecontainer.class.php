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

namespace Syndy\Api\Contracts\Template;

require_once dirname(__FILE__)."/../basecontract.class.php";
require_once dirname(__FILE__)."/valuecontainers/flatvaluecontainer.class.php";
require_once dirname(__FILE__)."/valuecontainers/arrayvaluecontainer.class.php";

use Syndy\Api\Contracts;

class ValueContainer extends Contracts\BaseContract {

	private $value;

	private $type;

	public function __construct($rawData, $type) {
		$this->type = $type;

		parent::__construct($rawData);
	}

	protected function parse($rawData) {
		$rawData = parent::parse($rawData);

		if ($rawData->Value === null)
			$this->value = null;

		if ($this->type == "array") {
			$this->value = new ArrayValueContainer($rawData);
		}
		elseif ($this->type == "object") {
			$this->value = ValueContainer::createObject($rawData);
		}
		elseif ($this->type == "enum") {
			$this->value = ValueContainer::createEnum($rawData);
		}
		else {
			$this->value = new FlatValueContainer($rawData);
		}		

		return $rawData;
	}

	public function hasValue() {
		return $this->value !== null;
	}

	public function __get($field) {
		if ($field == "value") {
			return $this->value !== null ? $this->value->value : null;
		}

		return parent::__get($field);
	}
}
?>