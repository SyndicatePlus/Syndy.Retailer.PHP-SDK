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

namespace Syndy\Api\Utility;

class Guid
{
	private $guid;

	/**
	 * Simply instantiates a new Guid and returns it.
	 *
	 * @access public
	 * @return Guid 		A new Guid instance
	 */
	public static function create() {
		return new Guid();
	}

	/**
	 * Constructor; this is where the Guid is actually generated or if the $guid
	 * parameter is specified, this is where it will be validated and set.
	 *
	 * @access public
	 * @param $guid 		Optional guid string
	 * @return void
	 */
	public function __construct($guid = "") {
		$guid = strtoupper($guid);
		if ( preg_match("/^[A-Z0-9]{8}-(?:[A-Z0-9]{4}-){3}[A-Z0-9]{12}$/", $guid) ) {
			$this->guid = $guid;
			return;
		}

		if ( function_exists('com_create_guid') === true ) {
	        $this->guid = trim(com_create_guid(), '{}');
	    }

	    $this->guid = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}

	public function __toString() {
		return $this->guid;
	}
}
?>