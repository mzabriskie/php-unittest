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

abstract class TestReporter {

    private static $reporters = array();

    public static function factory($name) {
        $reporter = self::getInstance($name);
        if ($reporter == null) {
            $reporter = self::getInstance(PHP_SAPI == 'cli' ? 'cli' : 'web');
        }
        return $reporter;
    }

    private static function getInstance($name) {
        if ($name == null) return null;
        $className = ucfirst($name) . 'TestReporter';

        if (!in_array($name, self::$reporters)) {
            $fileName = dirname(__FILE__) . '/reporters/' . $className . '.php';
            $reporter = null;
            if (file_exists($fileName)) {
                require_once($fileName);
                $reporter = new $className();
            }
            self::$reporters[$name] = $reporter;
        }
        return self::$reporters[$name];
    }

    abstract public function suiteStart($name);

    abstract public function suiteDone($name, $assertions);

    abstract public function testStart($name);

    abstract public function testDone($name, $assertions);

    abstract public function done($assertions, $duration);

}