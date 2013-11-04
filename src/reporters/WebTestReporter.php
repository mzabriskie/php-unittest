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

require_once('BaseWebTestReporter.php');

class WebTestReporter extends BaseWebTestReporter {
    public function suiteStart($name) {
        echo '<p/>';
        echo '<div>' . $this->bold($name) . '</div>';
    }

    public function suiteDone($name, $assertions) {}

    public function testStart($name) {}

    public function testDone($name, $assertions) {
        if (sizeof($assertions->failures) == 0 && sizeof($assertions->errors) == 0) {
            echo '<div>✔ ' . $name . '</div>';
        } else {
            echo '<div>' . $this->color('✖ ' . $name, 'red') . '</div>';
        }
    }

    public function done($assertions, $duration) {
        if (sizeof($assertions->failures) == 0 && sizeof($assertions->errors) == 0) {
            echo '<p/>';
            echo '<div>' . $this->color('OK:', 'green') . ' ' . $assertions->length . ' assertions (' . $duration . ')' . '</div>';
        } else {
            $this->reportErrors($assertions->failures, 'failure');
            $this->reportErrors($assertions->errors, 'error');
        }
    }

    private function reportErrors($errors, $label) {
        if (sizeof($errors) > 0) {
            $plural = sizeof($errors) > 1;
            echo '<p/>';
            echo '<div>' . $this->color('There ' . ($plural ? 'were' : 'was') . ' ' . sizeof($errors) . ' ' . $label . ($plural ? 's' : '') . ':', 'red') . '</div>';
            foreach ($errors as $k => $v) {
                echo '<div>' . $this->color($k, 'yellow') . '</div>';
                echo "<div>&nbsp;&nbsp;&nbsp;&nbsp;" . str_replace(PHP_EOL, '<br/>' . "&nbsp;&nbsp;&nbsp;&nbsp;", $v) . '</div>';
            }
        }
    }
}