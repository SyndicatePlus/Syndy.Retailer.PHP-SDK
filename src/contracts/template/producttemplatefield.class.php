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
require_once dirname(__FILE__)."/valuecontainers/objectvaluecontainer.class.php";
require_once dirname(__FILE__)."/valuecontainers/enumvaluecontainer.class.php";

use Syndy\Api\Contracts;

class ProductTemplateField extends Contracts\BaseContract {

	protected $id;

	protected $type;

	protected $key;

	protected $data;

	public function __construct($rawData) {
		parent::__construct($rawData);
	}

	protected function parse($rawData) {
		$rawData = parent::parse($rawData);

		$this->id = $rawData->Id;
		$this->type = $rawData->Type;
		$this->key = $rawData->Key;

		if ($rawData->Data === null)
			$this->data = null;

		if ($this->type == "array") {
			$this->data = new ArrayValueContainer($rawData->Data);
		}
		elseif ($this->type == "object") {
			$this->data = new ObjectValueContainer($rawData->Data);
		}
		elseif ($this->type == "enum") {
			$this->data = new EnumValueContainer($rawData->Data);
		}
		else {
			$this->data = new FlatValueContainer($rawData->Data);
		}

		return $rawData;
	}

	public function getId() {
		return $this->id;
	}

	public function getType() {
		return $this->type;
	}

	public function getKey() {
		return $this->key;
	}

	public function getData() {
		return $this->data;
	}

	public function isArray() {
		return $this->type == "array";
	}

	public function isObject() {
		return $this->type == "object";
	}

	public function isEnum() {
		return $this->type == "enum";
	}

	public function __get($field) {
		if ($field == "value") {
			if ($this->isArray()) {
				return $this->data;
			}

			if ($this->isObject()) {
				return $this->data;
			}

			return $this->data !== null ? $this->data->value : null;
		}

		return parent::__get($field);
	}
}
?>