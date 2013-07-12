<?php

// Recursively scan a directory looking for test sources
function recursive_scandir($dir) {
    $result = array();
    foreach (scandir($dir) as $name) {
        $path = $dir . '/' . $name;

        // No hidden paths
        if ($name[0] == '.') {
            continue;
        }
        // No non PHP files
        else if (substr($name, strlen($name) - 4) != '.php') {
            // ... unless it is a directory, then recurs
            if (is_dir($path)) {
                $result = array_merge($result, recursive_scandir($path));
            }
            continue;
        }

        // Only accept paths that are within a test directory
        if (strpos($path, '/test/')) {
            $result[] = $path;
        }
    }
    return $result;
}

// Run a test suite
function test_suite($testpath) {
    // Scan testpath for test classes and include them
    foreach (recursive_scandir($testpath) as $path) {
        include_once($path);
    }

    // Loop classes and run tests
    $suite = array();
    foreach (get_declared_classes() as $cls) {
        $ref = new ReflectionClass($cls);
        if ($ref->isSubclassOf('TestCase')) {
            $suite[] = $ref->newInstance();
        }
    }

    // Run test suite
    return TestRunner::runTestSuite($suite);
}

// Run a test case
function test_case($testpath, $test) {
    // Include test class
    include_once($testpath . '.php');

    // Normalize path and split it into pieces
    $testpath = str_replace('\\', '\/', $testpath);
    $parts = preg_split('/\//', $testpath);

    // Get class instance
    $cls = $parts[sizeof($parts) - 1];
    $ref = new ReflectionClass($cls);
    if (!$ref->isSubclassOf('TestCase')) {
        error('"' . $cls . '" is not an instance of TestCase');
        return 1;
    }
    $testcase = $ref->newInstance();

    // Run test case
    return TestRunner::runTestCase($testcase, $test);
}