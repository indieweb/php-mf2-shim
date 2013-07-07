<?php
namespace mf2\Shim;

use mf2\Shim;
use DateTime;
use Exception;

class Twitter extends Shim {

  /**
   * Kicks off the parsing routine
   * 
   * @return array An array containing all the µfs found in the current document
   */
  public function parse() {
    $items = array();

    foreach($this->cssQuery('div.tweet.permalink-tweet > p') as $node) {
      $item = array();
			
			$tweetText = $node->nodeValue;
      $authorName = $this->single('div.tweet.permalink-tweet .fullname');
      $authorNickname = $this->single('div.tweet.permalink-tweet .username');
      $authorPhoto = $this->querySelector('div.tweet.permalink-tweet .avatar')->getAttribute('src');
      $authorURL = 'https://twitter.com/' . $authorNickname;
			
      $publishedTS = $this->querySelector('div.permalink-tweet ._timestamp')->getAttribute('data-time');
			try {
				$publishedDateTime = DateTime::createFromFormat('U', $publishedTS)->format(DateTime::W3C);
			} catch (Exception $e) {
				$publishedDateTime = '';
			}
			
      $tags = array();

      $item['type'] = array('h-entry');
      $item['properties'] = array(
        'name' => array($tweetText),
				'content' => array($tweetText), // TODO: this should be C14N of node children
        'summary' => array($tweetText),
				'uid' => array(),
        'author' => array(
          array(
            'type' => array('h-card'),
            'properties' => array(
							'uid' => array(),
              'name' => array($authorName),
              'nickname' => array($authorNickname),
              'photo' => array($authorPhoto),
              'url' => array($authorURL)
            )
          )
        ),
        'url' => array(''),
        'published' => array($publishedDateTime),
        'category' => $tags,
				'comment' => array()
      );

			// Process replies
			$replies = array();
			
			foreach ($this->cssQuery('.permalink-replies .stream-items .tweet') as $reply) {
				$tweetTextEl = $this->xpath('.//p[contains(concat(" ", @class, " "), " tweet-text ")]', $reply)->item(0);
				
				$authorNameEl = $this->xpath('.//*[contains(concat(" ", @class, " "), " fullname ")]', $reply)->item(0);
				$authorNickEl = $this->xpath('.//*[contains(concat(" ", @class, " "), " username ")]', $reply)->item(0);
				$authorPhotoEl = $this->xpath('.//*[contains(concat(" ", @class, " "), " avatar ")]', $reply)->item(0);
				
				$publishedEl = $this->xpath('.//*[contains(concat(" ", @class, " "), " _timestamp ")]', $reply)->item(0);
				$publishedTimestamp = $publishedEl->getAttribute('data-time');
				try {
					$publishedDateTime = DateTime::createFromFormat('U', $publishedTimestamp)->format(DateTime::W3C);
				} catch (Exception $e) {
					$publishedDateTime = '';
				}
				
				$urlEl = $this->xpath('.//*[contains(concat(" ", @class, " "), " tweet-timestamp ")]', $reply)->item(0);
				
				$reply = array(
					'type' => array('h-entry'),
					'properties' => array(
						'uid' => array(),
						'name' => array($tweetTextEl->nodeValue),
						'content' => array($tweetTextEl->nodeValue), // TODO: use HTML
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
				
				$replies[] = $reply;
			}
			
			$item['properties']['comment'] = $replies;
			
      $items[] = $item;
    }

    return array('items' => array_values(array_filter($items)));
  }
}
