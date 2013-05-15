<?php

namespace mf2\Shim\test;

// Include Parser.php
$autoloader = require_once dirname(__DIR__) . '/../vendor/autoload.php';

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

