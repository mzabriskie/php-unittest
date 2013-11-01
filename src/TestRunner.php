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

require_once('Assert.php');
require_once('TestCase.php');
require_once('TestReporter.php');

class TestRunner {

	private static $currentTestCase = null;
    private static $currentTest = null;
    private static $countRun = 0;
    private static $countTotal = 0;
	private static $errors = array();
	private static $failures = array();

	static function getCountRun() {
		return self::$countRun;
	}

    static function getCountTotal() {
        return self::$countTotal;
    }

	static function getCountFailures() {
		return sizeof(self::$failures);
	}

	static function getCountErrors() {
		return sizeof(self::$errors);
	}

	static function getCountPass() {
		return (self::getCountRun() - self::getCountFailures());
	}

	static function addFailure(Exception $error) {
		self::$failures[self::getErrorKey()] = self::getErrorMessage($error);
	}

	static function getFailures() {
		return self::$failures;
	}

	static function addError(Exception $error) {
		self::$errors[self::getErrorKey()] = self::getErrorMessage($error);
	}

	static function getErrors() {
		return self::$errors;
	}

    static function getCurrentTestCase() {
        return self::$currentTestCase;
    }

    static function getCurrentTest() {
        return self::$currentTest;
    }

    static function runTestSuite($suite, $reporter = null) {
        $tests = array();

        foreach($suite as $testCase) {
            $tests[] = array(
                'testCase' => $testCase,
                'tests' => self::getTestsFromTestCase($testCase)
            );
        }

        return self::runTests($tests, $reporter);
    }

	static function runTestCase(TestCase $testCase, $test = null, $reporter = null) {
        $tests = array(
            'testCase' => $testCase,
            'tests' => array()
        );

		// Run all the tests if no specific test was specified
        if ($test == null) {
			$tests['tests'] = self::getTestsFromTestCase($testCase);
		}
        // Run a specific test
		else {
			$tests['tests'][] = new ReflectionMethod($testCase, $test);
		}

		return self::runTests(array($tests), $reporter);
	}

	private static function runTests($suite, $reporter = null) {
        $reporter = TestReporter::factory($reporter);

        self::$countRun = 0;
		self::$errors = array();
		self::$failures = array();
        date_default_timezone_set('America/Denver');

        // Override error handler
        $oldErrorHandler = set_error_handler('TestRuntime_errorHandler');

        // Populate count of all tests
        foreach ($suite as $temp) {
            self::$countTotal += sizeof($temp['tests']);
        }

		$suiteStart = microtime(true);
        $assertCount = 0;

        // Iterate all the test cases in the suite
        foreach ($suite as $temp) {
            $testCase = $temp['testCase'];
            $tests = $temp['tests'];
            self::$currentTestCase = $testCase;

            $reporter->suiteStart(get_class($testCase));

            // Iterate all the tests in the test case
            foreach ($tests as $test) {

                self::$currentTest = $test;
                $failures = array();
                $errors = array();

                try {
                    $testCase->setUp();
                } catch (Exception $e) {
                    self::addError($e);
                    $errors[] = $e;
                }

                try {
                    $test->invoke($testCase);
                } catch (AssertionError $e) {
                    self::addFailure($e);
                    $failures[] = $e;
                } catch (Exception $e) {
                    self::addError($e);
                    $errors[] = $e;
                }

                try {
                    $testCase->tearDown();
                } catch (Exception $e) {
                    self::addError($e);
                    $errors[] = $e;
                }

                self::$currentTest = null;
                self::$countRun++;

                $assertions = (object) array(
                    'failures' => $failures,
                    'errors' => $errors
                );
                $reporter->testDone($test->getName(), $assertions);
            }

            $assertCount += $testCase->count;
        }

        $suiteEnd = microtime(true);

        // Restore error handler
        if (is_callable($oldErrorHandler)) {
            set_error_handler($oldErrorHandler);
        }

        self::$currentTestCase = null;

        $assertions = (object) array(
            'length' => $assertCount,
            'failures' => self::getFailures(),
            'errors' => self::getErrors()
        );
        $reporter->done($assertions, self::formatDuration(floor($suiteEnd - $suiteStart) * 1000));

        return sizeof(self::getFailures()) == 0 && sizeof(self::getErrors()) == 0 ? 0 : 1;
	}

    private static function getTestsFromTestCase(TestCase $testCase) {
        $tests = array();

        $obj = new ReflectionObject($testCase);
        foreach ($obj->getMethods() as $method) {
            // Only methods prefixed with "test" that are public will be run
            if (strpos($method->getName(), 'test') === 0 && $method->isPublic()) {
                $tests[] = $method;
            }
        }

        return $tests;
    }

	private static function getErrorMessage(Exception $error) {
		return get_class($error) . ' ' . $error->getMessage() . ' in ' . $error->getFile() . '(' . $error->getLine() . ')' . PHP_EOL . $error->getTraceAsString();
	}

    private static function getErrorKey() {
        return get_class(self::$currentTestCase) . '#' . self::$currentTest->getName();
    }

    private static function formatDuration($timeInMillis) {
        $intervals = array(
            'd' => 86400000,
            'h' => 3600000,
            'm' => 60000,
            's' => 1000,
            'ms' =>1
        );

        $sb = '';

        foreach ($intervals as $k => $v) {
            $millis = floor($timeInMillis / $v);
            $timeInMillis %= $v;
            if ($millis > 0 || (strlen($sb) ==0 && $k == 'ms')) {
                if (strlen($sb) > 0) $sb .= ':';
                $sb .= $millis . $k;
            }
        }

        return $sb;
    }

}

class TestRuntimeError extends Exception {
    function __construct($message, $code, $file, $line) {
        parent::__construct($message, $code);

        $this->file = $file;
        $this->line = $line;
    }
}

function TestRuntime_errorHandler($errno, $errstr, $errfile, $errline) {
    TestRunner::addError(new TestRuntimeError($errstr, $errno, $errfile, $errline));

    return true;
}
