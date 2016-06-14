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

namespace Syndy\Api\Net;

/**
 * Parses the raw HTTP header lines returned through the $http_response_headers
 * global variable.
 * Source: http://php.net/manual/en/reserved.variables.httpresponseheader.php
 * Heavily adjusted, however.
 */
function parseHeaders($headers)
{
	$head = array();
	foreach ($headers as $k => $v)
	{
		if (preg_match("/^HTTP\/([0-9\.]+)\s+([0-9]+)/", $v, $out)) {
			$head["protocol_version"] = $out[1];
			$head["response_code"] = intval($out[2]);
		}
		else {
			$t = explode(":", $v, 2);
			if (isset($t[1])) {
				$head[trim($t[0])] = trim($t[1]);
			}
		}		
	}
	return $head;
}
?>