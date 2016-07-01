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

namespace Syndy\Api\Interpreters;

require_once dirname(__FILE__) . "/../contracts/template/producttemplatefield.class.php";

use Syndy\Api\Contracts;

abstract class BaseInterpreter {

	protected $field;

	public function __construct(Contracts\Template\ProductTemplateField $field) {
		$this->field = $field;

		$this->interpret();
	}

	protected abstract function interpret();

	protected function getSpecificFieldByType($type, $index = 1) {
		$counter = 1;
		foreach ($this->field->value as $childField) {
			if ($childField->type == $type && $counter++ == $index) {
				return $childField;
			}
		}
		return null;
	}

	public function __get($field) {
		if (!isset($this->$field)) {
			return null;
		}

		return $this->$field;
	}
}
?>