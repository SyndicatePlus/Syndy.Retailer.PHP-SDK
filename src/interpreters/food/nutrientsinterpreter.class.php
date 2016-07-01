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

namespace Syndy\Api\Interpreters\Food;

require_once dirname(__FILE__)."/../baseinterpreter.class.php";
require_once dirname(__FILE__)."/../../contracts/template/producttemplatefield.class.php";
require_once dirname(__FILE__)."/../../exceptions/syndyapiexception.class.php";

use Syndy\Api\Contracts;
use Syndy\Api\Exceptions;
use Syndy\Api\Interpreters;

class NutrientsInterpreter extends Interpreters\BaseInterpreter implements \Iterator, \ArrayAccess {

	protected $hasPortion;

	public $portion = null;

	private $data = array();

	public function __construct(Contracts\Template\ProductTemplateField $field) {
		parent::__construct($field);
	}

	protected function interpret() {
		$portionField = $this->getSpecificFieldByType("boolean");
		if ($portionField === null) {
			throw new Exceptions\SyndyApiException("Cannot find portion boolean field. Are you sure this is a Nutrients field?");
		}
		$this->hasPortion = $portionField->value;

		if ($this->hasPortion) {
			// Parse portion size & unit
			$this->portion = new \stdClass();
			$this->portion->size = $this->field->value->getChildFieldByType("int")->value;
			$this->portion->uom = $this->field->value->getChildFieldByType("enum")->value;
		}

		// Parse individual nutrient rows into $data array
		$this->parseNutrientRows();
	}

	private function parseNutrientRows() {
		$array = $this->field->value->getChildFieldByType("array");
		foreach ($array->value as $value) {
			$value = $value->value;

			$data = new \stdClass();
			$data->displayName = $value->getChildFieldById("d3550eb8-7f67-4fe9-be6e-909035138820")->value;
			$data->amounts = array();

			$uom = $value->getChildFieldById("9c4d4359-3fb4-4d30-a152-eef1be68b638")->value;
			$precision = $value->getChildFieldById("1390e08a-5545-4a64-baf8-1e75d1890ec7")->value;

			$value1 = new \stdClass();
			$value1->precision = $precision;
			$value1->amount = $value->getChildFieldById("eebac603-d7f8-43bb-882b-4df8ba2e0ee5")->value;
			$value1->gda = $value->getChildFieldById("af28a80e-89df-4468-a6d1-8f5a83305bb2")->value;
			$value1->uom = $uom;
			$data->amounts[] = $value1;

			if ($this->hasPortion) {
				$value2 = new \stdClass();
				$value2->precision = $precision;
				$value2->amount = $value->getChildFieldById("65d54ae7-6026-4552-b6d8-e10486325a0a")->value;
				$value2->gda = $value->getChildFieldById("a7dbd6ca-c53f-425c-9d8c-5cfdb158a584")->value;
				$value2->uom = $uom;
				$data->amounts[] = $value2;
			}

			$this->data[] = $data;
		}
	}

	public function hasPortion() {
		return $this->hasPortion;
	}

	public function getPortion() {
		return $this->portion;
	}

	public function getNutrientsCount() {
		return count($this->data);
	}

	public function __get($field) {
		if ($field == "count") {
			return $this->getNutrientsCount();
		}

		return parent::__get($field);
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