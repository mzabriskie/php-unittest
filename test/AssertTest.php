<?php

class AssertTest extends TestCase {
    function testFail() {
        $error = false;
        try {
            $this->fail('Failure!');
        } catch (AssertionError $e) {
            $error = true;
        }

        if (!$error) {
            throw new AssertionError('AssertionError was not thrown');
        }
    }

    function testAssertTrue() {
        $this->assertTrue(true);

        $error = false;
        try {
            $this->assertTrue(false);
        } catch (AssertionError $e) {
            $error = true;
        }

        if (!$error) {
            $this->fail('AssertionError was not thrown');
        }
    }

    function testAssertFalse() {
        $this->assertFalse(false);

        $error = false;
        try {
            $this->assertFalse(true);
        } catch (AssertionError $e) {
            $error = true;
        }

        if (!$error) {
            $this->fail('AssertionError was not thrown');
        }
    }

    function testAssertEquals() {
        $this->assertEquals(123, 123);
        $this->assertEquals(true, true);
        $this->assertEquals('foo', 'foo');
        $this->assertEquals(array(), array());
        $this->assertEquals(new stdClass(), new stdClass());

        $error = false;
        try {
            $this->assertEquals(123, 456);
        } catch (AssertionError $e) {
            $error = true;
        }

        if (!$error) {
            $this->fail('AssertionError was not thrown');
        }
    }

    function testAssertNotEquals() {
        $this->assertNotEquals(123, 456);
        $this->assertNotEquals(true, false);
        $this->assertNotEquals('foo', 'bar');

        $error = false;
        try {
            $this->assertNotEquals(123, 123);
        } catch (AssertionError $e) {
            $error = true;
        }

        if (!$error) {
            $this->fail('AssertionError was not thrown');
        }
    }

    function testAssertNull() {
        $this->assertNull(null);

        $error = false;
        try {
            $this->assertNull('foo');
        } catch (AssertionError $e) {
            $error = true;
        }

        if (!$error) {
            $this->fail('AssertionError was not thrown');
        }
    }

    function testAssertNotNull() {
        $this->assertNotNull('foo');

        $error = false;
        try {
            $this->assertNotNull(null);
        } catch (AssertionError $e) {
            $error = true;
        }

        if (!$error) {
            $this->fail('AssertionError was not thrown');
        }
    }

    function testAssertSame() {
        $a = new stdClass();
        $b = new stdClass();
        $this->assertSame($a, $a);

        $error = false;
        try {
            $this->assertSame($a, $b);
        } catch (AssertionError $e) {
            $error = true;
        }

        if (!$error) {
            $this->fail('AssertionError was not thrown');
        }
    }

    function testAssertNotSame() {
        $a = new stdClass();
        $b = new stdClass();
        $this->assertNotSame($a, $b);

        $error = false;
        try {
            $this->assertNotSame($a, $a);
        } catch (AssertionError $e) {
            $error = true;
        }

        if (!$error) {
            $this->fail('AssertionError was not thrown');
        }
    }
}