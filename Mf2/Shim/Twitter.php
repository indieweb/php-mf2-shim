<?php
namespace Mf2\Shim;

use Mf2;
use DateTime;
use DOMElement;
use Exception;

function parseTwitter($html, $url=null) {
	$parser = new Twitter($html, $url, false);
	list($rels, $alternates) = $parser->parseRelsAndAlternates();
	return array_merge(array('rels' => $rels, 'alternates' => $alternates), $parser->parse());
}

class Twitter extends Mf2\Parser {
	public function parseTweet(DOMElement $el, $parseReplies=true) {
		$tweetTextEl = $this->query('.//p' . Mf2\xpcs('tweet-text'), $el)->item(0);

		$authorNameEl = $this->query('.//*' . Mf2\xpcs('fullname'), $el)->item(0);
		$authorNickEl = $this->query('.//*' . Mf2\xpcs('username'), $el)->item(0);
		$authorPhotoEl = $this->query('.//*' . Mf2\xpcs('avatar'), $el)->item(0);

		$publishedEl = $this->query('.//*' . Mf2\xpcs('_timestamp'), $el)->item(0);
		$publishedTimestamp = $publishedEl->getAttribute('data-time');
		try {
			$publishedDateTime = DateTime::createFromFormat('U', $publishedTimestamp)->format(DateTime::W3C);
		} catch (Exception $e) {
			$publishedDateTime = '';
		}

		$urlEl = $this->query('.//*' . Mf2\xpcs('tweet-timestamp'), $el)->item(0);
		
		$htmlTweetContent = '';
		foreach ($tweetTextEl->childNodes as $node) {
			$htmlTweetContent .= $node->C14N();
		}
		
		$tweet = array(
			'type' => array('h-entry'),
			'properties' => array(
				'uid' => array(),
				'name' => array($tweetTextEl->nodeValue),
				'content' => array(array(
					'value' => $tweetTextEl->nodeValue,
					'html' =>  $htmlTweetContent
				)),
				'summary' => array($tweetTextEl->nodeValue),
				'url' => array($urlEl->getAttribute('href')),
				'published' => array($publishedDateTime),
				'author' => array(
						array(
						'type' => array('h-card'),
						'properties' => array(
							'uid' => array(),
							'name' => array($authorNameEl->nodeValue),
							'nickname' => array($authorNickEl->nodeValue),
							'photo' => array($authorPhotoEl->getAttribute('src')),
							'url' => array('https://twitter.com/' . $authorNickEl->nodeValue)
						)
					)
				)
			)
		);
		
		if ($parseReplies) {
			foreach ($this->query('//*' . Mf2\xpcs('permalink-replies') . '//*' . Mf2\xpcs('tweet')) as $reply) {
				$tweet['properties']['comment'][] = $this->parseTweet($reply, false);
			}
		}
		
		return $tweet;
	}
	
	/**
   * Parse
   * 
   * @return array
   */
  public function parse() {
    $items = array();

    foreach($this->query('//div' . Mf2\xpcs('tweet', 'permalink-tweet')) as $node) {
      $items[] = $this->parseTweet($node);
    }

    return array('items' => array_values(array_filter($items)));
  }
}
