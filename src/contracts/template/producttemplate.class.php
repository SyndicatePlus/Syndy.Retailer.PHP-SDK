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
require_once dirname(__FILE__)."/producttemplatefield.class.php";

use Syndy\Api\Contracts;

class ProductTemplate extends Contracts\BaseContract {

	protected $id;

	protected $name;

	protected $children = array();

	protected $fields = array();

	public function __construct($rawData) {
		parent::__construct($rawData);
	}

	protected function parse($rawData) {
		$rawData = parent::parse($rawData);

		$this->id = $rawData->Id;
		$this->name = $rawData->Name;

		foreach ($rawData->Children as $child) {
			$this->children[] = new ProductTemplate($child);
		}

		foreach ($rawData->Fields as $field) {
			$this->fields[] = new ProductTemplateField($field);
		}

		return $rawData;
	}

	public function getId() {
		return $this->id;		
	}

	public function getName() {
		return $this->name;
	}

	public function getChildren() {
		return $this->children;
	}

	public function getFields($allFields = true) {
		if ($allFields) {
			$fields = $this->fields;
			foreach ($this->children as $childTemplate) {
				$fields = array_merge($fields, $childTemplate->getFields($allFields)); // Recursion
			}
			return $fields;
		}
		
		return $this->fields;
	}
}
?>