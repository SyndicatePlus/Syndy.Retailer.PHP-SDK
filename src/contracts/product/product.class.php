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

namespace Syndy\Api\Contracts\Product;

require_once dirname(__FILE__)."/../basecontract.class.php";
require_once dirname(__FILE__)."/../../exceptions/syndyapiexception.class.php";
require_once dirname(__FILE__)."/productsummary.class.php";
require_once dirname(__FILE__)."/../template/producttemplate.class.php";

use Syndy\Api\Exceptions;
use Syndy\Api\Contracts;

class Product extends Contracts\BaseContract {

	protected $dateLastUpdate;

	protected $barcode;

	protected $summary;

	protected $template;

	protected $id;

	public function __construct($rawData) {
		$this->parse($rawData);
	}

	protected function parse($rawData) {
		$rawData = parent::parse($rawData);

		$this->id = $rawData->Id;
		$this->barcode = $rawData->Barcode;
		$this->dateLastUpdate = $rawData->DateLastUpdate;
		$this->summary = new ProductSummary($rawData->Summary);
		$this->template = new Contracts\Template\ProductTemplate($rawData->Template);

		return $rawData;
	}

	public function getId() {
		return $this->id;
	}

	public function getBarcode() {
		return $this->barcode->Value;
	}

	public function getName() {
		return $this->summary->getName();
	}

	public function getShortDescription() {
		return $this->summary->getShortDescription();
	}

	public function getBrand() {
		return $this->summary->getBrand();
	}

	public function getDateLastUpdate() {
		return $this->dateLastUpdate;
	}

	public function getTemplate() {
		return $this->template;
	}

	/** 
	 * Convenience method for accessing all of the product template's fields. Including
	 * child templates' fields.
	 *
	 * @return array 		Array of ProductTemplateField instances.
	 */
	public function getFields() {
		return $this->template->getFields();
	}

	public function findField($name) {
		foreach ($this->getFields() as $field) {
			if (strtolower($field->key) == strtolower($name)) {
				return $field;
			}
		}

		return null;
	}

	public function __get($field) {
		if ($field == "brand") {
			return $this->summary->brand;
		}
		elseif($field == "name") {
			return $this->summary->name;
		}
		elseif($field == "shortDescription") {
			return $this->summary->shortDescription;
		}
		elseif($field == "image") {
			return $this->summary->image;
		}
		elseif(($foundField = $this->findField($field)) !== null) {
			return $foundField;
		}

		return parent::__get($field);
	}
}
?>