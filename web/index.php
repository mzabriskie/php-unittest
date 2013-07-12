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

// Write an error message to the browser
function error($msg) {
    echo '<div style="color:#c02000;">Error: ' . $msg . '</div>';
}

/**
 * Main entry point for running tests
 *
 * @param $args array the args received from $_GET
 * @return int the exit status code
 */
function main ($args) {
    // Find path to phyllis source
    $home = null;
    if ($_ENV['PHYLLIS_HOME'] != null) {
        $home = $_ENV['PHYLLIS_HOME'];
    } else {
        $home = dirname(__FILE__) . '/../';
    }

    if (!is_file($home . '/src/TestRunner.php')) {
        error('Incorrect path to phyllis "' . $home . '"');
        return 1;
    }

    require_once($home . '/src/TestRunner.php');
    require_once($home . '/lib/runtime.php');

    list($testpath, $test) = preg_split('/#/', $args['testpath']);

    if (is_dir($testpath)) {
        return test_suite($testpath);
    } else if (is_file($testpath . '.php')) {
        return test_case($testpath, $test);
    } else {
        error('"' . $testpath . '" is not a valid directory');
        return 1;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Phyllis Test Runner</title>
    <meta charset="utf-8">
</head>
<body>

    <?php main($_GET); ?>

</body>
</html>