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

require_once(dirname(__FILE__) . '/../TestReporter.php');

abstract class BaseWebTestReporter extends TestReporter {

    private static $colors = array(
        'black' => '#000000',
        'dark_gray' => '#686868',
        'blue' => '#0024c5',
        'light_blue' => '#6971f2',
        'green' => '#00c200',
        'light_green' => '#5ff968',
        'cyan' => '#00bcc4',
        'light_cyan' => '#54fcff',
        'red' => '#c02000',
        'light_red' => '#ff6d61',
        'purple' => '#cb2ca3',
        'light_purple' => '#ff75f1',
        'yellow' => '#c7c500',
        'light_yellow' => '#fffb60',
        'light_gray' => '#c7c7c7',
        'white' => '#ffffff'
    );

    public function bold($text) {
        return '<b>' . $text . '</b>';
    }

    public function color($text, $color) {
        if (!isset(self::$colors[$color])) return $text;
        return '<span style="color:' . self::$colors[$color] . ';">' . $text . '</span>';
    }

}