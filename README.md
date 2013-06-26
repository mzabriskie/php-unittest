# phyllis #

Unit Testing for PHP

## Writing Tests ##

#### Test Classes

To define a test you must extend the <code>TestCase</code> class.

```php
<?php
class SampleTest extends TestCase {}
```

#### Test Methods

Any method in a <code>TestCase</code> that is public and is prefixed with "test" will be run as a test.

```php
<?php
class SampleTest extends TestCase {
	function testSomething() {}
}
```

#### setUp and tearDown

Often when writing a <code>TestCase</code> each test will have similar or identical boilerplate. Using the <code>setUp</code> and <code>tearDown</code> methods allow you to extract this boilerplate logic. These methods are called before and after each test.

```php
<?php
require 'File.php';

class FileTest extends TestCase {
	private $file;
	
	function setUp() {
		parent::setUp();
		
		$this->file = new File('sample_file.txt');
	}
	
	function tearDown() {
		// Make sure filesystem is cleaned up and file isn't left hanging around
		if (file_exists('sample_file.txt')) {
			unlink('sample_file.txt');
		}
	
		$this->file = null;
		
		parent::tearDown();
	}
	
	function testWriteFile() {
		$this->file->setContent('Foo');
		$this->file->write();
		
		// If this assertion fails the method will short circuit here.
		// There will be no chance to clean up the filesystem.
		// tearDown method above will be invoked to allow clean up to happen.
		$this->assertTrue($this->file->exists());
	}
}
```

#### Making Assertions

This is the Assertion API:

- fail($message)
- assertTrue($condition, $message)
- assertFalse($condition, $message)
- assertEquals($expected, $actual, $message)
- assertNotEquals($expected, $actual, $message)
- assertNull($object, $message)
- assertNotNull($object, $message)
- assertSame($expected, $actual, $message)
- assertNotSame($expected, $actual, $message)

## Running Tests ##

#### Running a specific TestCase

You can run a specific <code>TestCase</code> by specifying the path to the test.

    $ phyllis /Workspace/MyProject/test/FileTest

This will run all tests in the <code>FileTest</code> class and assumes that the file is named <em>FileTest.php<em>.

#### Running a specific test

If you want to only run a specific test within a <code>TestCase</code> you can append the name of the test to the path.

    $ phyllis /Workspace/MyProject/test/FileTest#testWriteFile

This will run only the <code>testWriteFile</code> method in <code>FileTest</code>

#### Testing a module

Tests need to be placed in a directory called "test". The test runner will recursively look for any test classes located under this directory.

	$ phyllis /Workspace/MyProject
	
This will run all tests located under the /Workspace/MyProject/test directory.

#### Testing multiple modules

You can also test multiple modules at a time. Consider the following directory structure.
	
	Workspace
	|	MyProject
	|	|	ModuleA
	|	|	|	src
	|	|	|	test
	|	|	ModuleB
	|	|	|	src
	|	|	|	test
	|	|	ModuleC
	|	|	|	src
	|	|	|	test
	
If we used the same command as before:

	$ phyllis /Workspace/MyProject
	
The test runner will run all the test classes under ModuleA/test, ModuleB/test and ModuleC/test.

## Installing phyllis ##

First you will need to clone this repo. Once this has been done you need to make phyllis available to your system. Add the following line to <em>.bash_profile</em>

	export PATH="/path/to/phyllis/bin:$PATH"
	
If you opt to place the phyllis executable elsewhere (say <em>/usr/bin</em>) you will need to add another system variable so phyllis knows where to find the source files.

	export PHYLLIS_HOME="/path/to/phyllis"