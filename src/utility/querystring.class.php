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

/**
 * The QueryString utility wraps textual querystrings with a layer of
 * functionality that makes it easier to extract data and modify it.
 */
class QueryString implements \Iterator, \ArrayAccess
{
	protected $data;
	protected $valid;
	
	public function __construct($queryString = "") {
		$this->parse($queryString);
	}
	
	public function parse($queryString) {
		/* No matter what happens, the querystring is reset. */
		$this->reset();
		
		if ( strlen($queryString) <= 1 ) return false;
		
		/* If the string starts with a questionmark, remove it. */
		if ( strpos($queryString, "?") === 0 ) {
			$queryString = substr($queryString, 1);
		}
		
		/* Url decode. */
		$queryString = rawurldecode($queryString);
		parse_str($queryString, $this->data);
		
		$this->valid = true;
	}
	
	public function get($key) {
		if ( isset($this->data[$key]) ) return $this->data[$key];
		return null;
	}
	
	public function set($key, $value) {
		$this->data[$key] = $value;
		$this->valid = true;
	}
	
	public function __toString() {
		if ( !$this->valid ) return "";
		
		$output = "";
		foreach ( $this->data as $key => $value ) $output .= $key ."=". urlencode($value) ."&";
		return substr($output, 0, strlen($output) - 1);
	}
	
	private function reset() {
		$this->data = array();
		$this->valid = false;
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