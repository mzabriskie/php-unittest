<?php

/*

Copyright (c) 2013 by Matt Zabriskie

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

abstract class Assert {

    public $count = 0;

	function fail($message) {
		throw new AssertionError($message);
	}

	function assertTrue($condition, $message = null) {
        $this->count++;
		if ($condition !== true) {
			$this->fail($this->assertMessage('true', $condition, $message));
		}
	}

	function assertFalse($condition, $message = null) {
        $this->count++;
		if ($condition !== false) {
			$this->fail($this->assertMessage('false', $condition, $message));
		}
	}

	function assertEquals($expected, $actual, $message = null) {
        $this->count++;
		if ($expected != $actual) {
			$this->fail($this->assertMessage($expected, $actual, $message));
		}
	}

    function assertNotEquals($expected, $actual, $message = null) {
        $this->count++;
		if ($expected == $actual) {
			$this->fail($this->assertMessage($expected, $actual, $message));
		}
	}

    function assertNull($object, $message = null) {
        $this->count++;
		if ($object !== null) {
			$this->fail($this->assertMessage('null', $object, $message));
		}
	}

    function assertNotNull($object, $message = null) {
        $this->count++;
		if ($object === null) {
			$this->fail($this->assertMessage('not null', 'null', $message));
		}
	}

	function assertSame($expected, $actual, $message = null) {
        $this->count++;
		if ($expected !== $actual) {
			$this->fail($this->assertMessage($expected, $actual, $message));
		}
	}

	function assertNotSame($expected, $actual, $message = null) {
        $this->count++;
		if ($expected === $actual) {
			$this->fail($this->assertMessage($expected, $actual, $message));
		}
	}

	private function assertMessage($expected, $actual, $message) {
        if (is_object($expected)) {
            $expected = method_exists($expected, 'toString') ? $expected->toString() : '[object]';
        }
        if (is_object($actual)) {
            $actual = method_exists($actual, 'toString') ? $actual->toString() : '[object]';
        }
		return ($message != null ? $message : 'Assertion failed: expected<' . $expected . '>, actual<' . $actual . '>');
	}

}

class AssertionError extends Exception {
    function __construct($message) { parent::__construct($message); }
}
