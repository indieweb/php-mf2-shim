<?php

namespace mf2\Shim\test;

$autoloader = require_once dirname(__DIR__) . '/../vendor/autoload.php';

use mf2\Parser,
    mf2\Shim,
    PHPUnit_Framework_TestCase,
    DateTime;

/**
 * Shim Test
 */
class InstagramTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        date_default_timezone_set('Europe/London');
    }

    public function testHEntryFromTweet() {
        $input = file_get_contents('./tests/mf2/example-instagram.html');
        $parser = new mf2\Shim\Instagram($input);
        $output = $parser->parse();

        $this->assertArrayHasKey('items', $output);
        $this->assertArrayHasKey('content', $output['items'][0]['properties']);
    }

}

