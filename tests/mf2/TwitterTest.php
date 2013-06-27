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

    public function testParsesHEntryFromTweetPermalinkHtml() {
        $input = file_get_contents('./tests/mf2/example-twitter.html');
        $parser = new Shim\Twitter($input);
        $output = $parser->parse();
				
        $this->assertArrayHasKey('items', $output);
				$this->assertCount(1, $output['items']);
				
				return $output;
    }
		
		/**
		 * @depends testParsesHEntryFromTweetPermalinkHtml
		 */
		public function testHEntryHasContent($output) {
			$this->assertArrayHasKey('content', $output['items'][0]['properties']);
		}
		
		/**
		 * @depends testParsesHEntryFromTweetPermalinkHtml
		 */
		public function testHEntryHasAuthor($output) {
			$this->assertArrayHasKey('author', $output['items'][0]['properties']);
			
			$author = $output['items'][0]['properties']['author'][0];
			
			$this->assertEquals('Aaron Parecki', $author['properties']['name'][0]);
			$this->assertEquals('https://twitter.com/@aaronpk', $author['properties']['url'][0]);
			$this->assertEquals('https://si0.twimg.com/profile_images/3657148842/934fb225b84b8fd3effe5ab95bb18005_normal.jpeg', $author['properties']['photo'][0]);
		}
		
		/**
		 * @depends testParsesHEntryFromTweetPermalinkHtml
		 */
		public function testHEntryHasComments($output) {
			$this->assertArrayHasKey('comment', $output['items'][0]['properties']);
			$comments = $output['items'][0]['properties']['comment'];
			
			$this->assertCount(1, $comments);
		}
}

