<?php

namespace mf2\Shim\test;

use mf2\Parser,
    mf2\Shim,
    PHPUnit_Framework_TestCase,
    DateTime;

/**
 * Shim Test
 */
class TwitterTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        date_default_timezone_set('Europe/London');
    }

    public function testHEntryFromTweet() {
        $input = file_get_contents('./tests/mf2/example-twitter.html');
        $parser = new Shim\Twitter($input);
        $output = $parser->parse();

        $this->assertArrayHasKey('items', $output);
        $this->assertArrayHasKey('content', $output['items'][0]['properties']);
        # $properties = $output['items'][0]['properties'];
        # $this->assert;
    }

}

