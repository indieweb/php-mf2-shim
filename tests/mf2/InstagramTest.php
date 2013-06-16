<?php

namespace mf2\Shim\test;

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

    public function testHEntryFromPhoto() {
        $input = file_get_contents('./tests/mf2/example-instagram.html');
        $parser = new Shim\Instagram($input);
        $output = $parser->parse();

        $this->assertArrayHasKey('items', $output);
        $this->assertArrayHasKey(0, $output['items']);
        $this->assertArrayHasKey('content', $output['items'][0]['properties']);
    }

}

