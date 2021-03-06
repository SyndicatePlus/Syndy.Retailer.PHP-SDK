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

require_once dirname(__FILE__)."/../../basecontract.class.php";
require_once dirname(__FILE__)."/../producttemplatefield.class.php";

use Syndy\Api\Contracts;

class ObjectValueContainer extends Contracts\BaseContract implements \Iterator, \ArrayAccess {

	protected $data = array();

	public function __construct($rawData) {
		parent::__construct($rawData);
	}

	protected function parse($rawData) {
		$rawData = parent::parse($rawData);

		foreach ($rawData->Value as $childField) {
			$this->data[] = new Contracts\Template\ProductTemplateField($childField);
		}

		return $rawData;
	}

	public function getChildFields() {
		return $this->data;
	}

	public function getChildFieldByType($type, $index = 1) {
		$counter = 1;
		foreach ($this->data as $childField) {
			if ($childField->type == $type && $counter++ == $index) {
				return $childField;
			}
		}
		return null;
	}

	public function getChildFieldById($id) {
		foreach ($this->data as $childField) {
			if ($childField->id == $id) {
				return $childField;
			}
		}
		return null;
	}

	public function __get($field) {
		foreach ($this->data as $childField) {
			if ($childField->key == $field) {
				return $childField;
			}
		}
		
		return parent::__get($field);
	}

	public function __toString() {
		return "[ComplexType]";
	}

	/*///////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
						ARRAYACCESS IMPLEMENTATION
	\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\//////////////////////////////*/

	/**
	 * @access public
	 * @param $offset
	 * @return boolean
	 */
	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	/**
	 * @access public
	 * @param $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		return (isset($this->data[$offset]) ? $this->data[$offset] : null);
	}

	/** 
	 * @access public
	 * @param $offset
	 * @param $value
	 * @return void
	 */
	public function offsetSet($offset, $value) {
		if ( !is_null($offset) ) {
            $this->data[$offset] = $value;
        }
	}

	/**
	 * @access public
	 * @param $offset
	 * @return void
	 */
	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	/**
	 * @access public
	 * @return void
	 */
	public function rewind() {
    	reset($this->data);
    }

    /**
     * @access public
     * @return mixed
     */
    public function current() {
		return current($this->data);
    }

    /**
     * @access public
     * @return mixed
     */
    public function key() {
		return key($this->data);
    }

    /**
     * @access public
     * @return mixed
     */
    public function next() {
		return next($this->data);
    }

    /** 
     * @access public
     * @return boolean
     */
    public function valid() {
		return $this->current() !== false;
    }
}
?>