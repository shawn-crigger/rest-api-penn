<?php

use PHPUnit\Framework\TestCase;

/**
* Trivial dummy test used to confirm test configuration working.
*/
class DummyTest extends TestCase
{
    /**
    * Trivial dummy test guaranteed to succeed.
    */
    public function testAssertEquals()
    {
        $this->assertEquals('abc', 'abc');
    }
}
