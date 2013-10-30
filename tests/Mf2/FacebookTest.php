<?php

namespace Mf2\Shim\Test;

use Mf2;
use PHPUnit_Framework_TestCase;

/**
 * Shim Test
 */
class FacebookTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		date_default_timezone_set('Europe/London');
	}

	public function testParsesHEntryFromPostPermalinkHtml() {
		$input = file_get_contents('./tests/mf2/example-facebook.html');
		$output = Mf2\Shim\parseFacebook($input, 'https://www.facebook.com/barnaby.walters/posts/587281857995088');
		
		print_r($output);
		
		$this->assertArrayHasKey('items', $output);
		$this->assertCount(1, $output['items']);

		return $output;
	}
}
