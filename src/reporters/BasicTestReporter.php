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

require_once('ConsoleTestReporter.php');

class BasicTestReporter extends ConsoleTestReporter {

    public function suiteStart($name) {
        echo PHP_EOL . $this->bold($name) . PHP_EOL;
    }

    public function suiteDone($name) {}

    public function testStart($name) {}

    public function testDone($name, $assertions) {
        if (sizeof($assertions->failures) == 0 && sizeof($assertions->errors) == 0) {
            echo '✔ ' . $name . PHP_EOL;
        } else {
            echo $this->color('✖ ' . $name, 'red') . PHP_EOL;
        }
    }

    public function done($assertions, $duration) {
        if (sizeof($assertions->failures) == 0 && sizeof($assertions->errors) == 0) {
            echo PHP_EOL . $this->color('OK:', 'light_green') . ' ' . $assertions->length . ' assertions (' . $duration . ')' . PHP_EOL;
        } else {
            $this->reportErrors($assertions->failures, 'failure');
            $this->reportErrors($assertions->errors, 'error');
        }
    }

    private function reportErrors($errors, $label) {
        if (sizeof($errors) > 0) {
            $plural = sizeof($errors) > 1;
            echo PHP_EOL . $this->color('There ' . ($plural ? 'were' : 'was') . ' ' . sizeof($errors) . ' ' . $label . ($plural ? 's' : '') . ':', 'red') . PHP_EOL;
            foreach ($errors as $k => $v) {
                echo $this->color($k, 'yellow') . PHP_EOL;
                echo "\t" . str_replace(PHP_EOL, PHP_EOL . "\t", $v) . PHP_EOL;
            }
        }
    }

}