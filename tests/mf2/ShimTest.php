<?php

namespace mf2\Shim\test;

use mf2\Parser,
    mf2\Shim,
    PHPUnit_Framework_TestCase,
    DateTime;

/**
 * Shim Test
 */
class ShimTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        date_default_timezone_set('Europe/London');
    }

    public function testMicroformatNameFromClassReturnsFullRootName() {
        $expected = array('h-card');
        $actual = Parser::mfNamesFromClass('someclass h-card someotherclass', 'h-');

        $this->assertEquals($actual, $expected);
    }

}

