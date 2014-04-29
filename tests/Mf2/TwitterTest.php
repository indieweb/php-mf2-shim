<?php

namespace Mf2\Shim\Test;

use Mf2;
use PHPUnit_Framework_TestCase;

/**
 * Shim Test
 */
class TwitterTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		date_default_timezone_set('Europe/London');
	}

	public function testParsesHEntryFromTweetPermalinkHtml() {
		$input = file_get_contents('./tests/Mf2/example-twitter.html');
		$output = Mf2\Shim\parseTwitter($input);
		$this->assertArrayHasKey('items', $output);
		$this->assertCount(1, $output['items']);

		return $output;
	}

	/**
	 * @depends testParsesHEntryFromTweetPermalinkHtml
	 * @todo what’s with the space before the closing paren? More nbsp annoyance? Fix
	 */
	public function testHEntryHasContent($output) {
		$this->assertArrayHasKey('content', $output['items'][0]['properties']);
		$this->assertEquals('Started off the week getting up at 5am, ended the week going to sleep at 5am. I am my own little timezone. (http://aaron.pk/n4Px1 )', $output['items'][0]['properties']['content'][0]['value']);
	}

	/**
	 * @depends testParsesHEntryFromTweetPermalinkHtml
	 */
	public function testHEntryHasPublished($output) {
		$this->assertArrayHasKey('published', $output['items'][0]['properties']);
		$this->assertEquals('2013-05-13T02:30:56+00:00', $output['items'][0]['properties']['published'][0]);
	}

	/**
	 * @depends testParsesHEntryFromTweetPermalinkHtml
	 */
	public function testHEntryHasAuthor($output) {
		$this->assertArrayHasKey('author', $output['items'][0]['properties']);

		$author = $output['items'][0]['properties']['author'][0];

		$this->assertEquals('Aaron Parecki', $author['properties']['name'][0]);
		$this->assertEquals('https://twitter.com/aaronpk', $author['properties']['url'][0]);
		$this->assertEquals('https://si0.twimg.com/profile_images/3657148842/934fb225b84b8fd3effe5ab95bb18005_normal.jpeg', $author['properties']['photo'][0]);
	}

	/**
	 * @depends testParsesHEntryFromTweetPermalinkHtml
	 */
	public function testHEntryHasComments($output) {
		$this->assertArrayHasKey('comment', $output['items'][0]['properties']);
		$comments = $output['items'][0]['properties']['comment'];

		$this->assertCount(1, $comments);

		$comment = $comments[0]['properties'];
		$author = $comment['author'][0]['properties'];

		$this->assertEquals('Rachel Kalmar', $author['name'][0]);
		$this->assertEquals('@grapealope', $author['nickname'][0]);
	}
	
	public function testParsesHCardHEntriesFromProfilePage() {
		$input = file_get_contents('./tests/Mf2/example-twitter-2.html');
		$output = Mf2\Shim\parseTwitter($input, 'https://twitter.com/briansuda');
		$this->assertArrayHasKey('items', $output);
		return $output;
	}
	
	public function testPreprocessesTweetContent() {
		$input = file_get_contents('./tests/Mf2/kartikprabhu-twitter.html');
		$output = Mf2\shim\parseTwitter($input, 'https://twitter.com/kartik_prabhu/status/449032538476929024');
		$this->assertEquals('The #indieweb or: how I learnt to stop worrying and love the #blog. Comes about a year since I went indie (http://kartikprabhu.com/article/indieweb-love-blog)', $output['items'][0]['properties']['content'][0]['value']);
	}
}
