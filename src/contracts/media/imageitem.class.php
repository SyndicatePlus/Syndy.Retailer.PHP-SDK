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

namespace Syndy\Api\Contracts\Media;

require_once dirname(__FILE__)."/mediaitembase.class.php";

class ImageItem extends MediaItemBase {

	protected $properties;

	protected $ownershipType;

	public function __construct($rawData) {
		parent::__construct($rawData);
	}

	protected function parse($rawData) {
		$rawData = parent::parse($rawData);

		$this->properties = new \stdClass();
		$this->properties->width = $rawData->Properties->Width;
		$this->properties->height = $rawData->Properties->Height;
		$this->ownershipType = $rawData->OwnershipType;

		return $rawData;
	}

	public function getProperties() {
		return $this->properties;
	}

	public function getOwnershipType() {
		return $this->ownershipType;
	}
}
?>