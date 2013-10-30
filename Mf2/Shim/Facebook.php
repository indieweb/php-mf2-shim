<?php
namespace Mf2\Shim;

use Mf2;
use DateTime;
use DOMElement;
use Exception;

function parseFacebook($html, $url=null) {
	$parser = new Facebook($html, $url, false);
	list($rels, $alternates) = $parser->parseRelsAndAlternates();
	return array_merge(array('rels' => $rels, 'alternates' => $alternates), $parser->parse());
}

class Facebook extends Mf2\Parser {
	public function parsePost(DOMElement $el) {
		/*
		$htmlTweetContent = '';
		foreach ($tweetTextEl->childNodes as $node) {
			$htmlTweetContent .= $node->C14N();
		}*/
		
		$authorPhoto = $this->query('.//*' . Mf2\xpcs('fbStreamPermalinkHeader') . '//*' . Mf2\xpcs('profilePic'))->item(0)->getAttribute('src');
		$authorLink = $this->query('.//*' . Mf2\xpcs('permalinkHeaderInfo') . '/a')->item(0);
		$authorUrl = $authorLink->getAttribute('href');
		$authorName = trim($authorLink->textContent);
		
		$post = array(
			'type' => array('h-entry'),
			'properties' => array(
				'author' => array(
					'type' => array('h-card'),
					'properties' => array(
						'photo' => array($authorPhoto),
						'name' => array($authorName),
						'url' => array($authorUrl)
					)
				)
			)
		);

		return $post;
	}
	
	/**
   * Parse
   * 
   * @return array
   */
  public function parse() {
    $items = array();

    foreach($this->query('//*[@id="content"]') as $node) {
      $items[] = $this->parsePost($node);
    }

    return array('items' => array_values(array_filter($items)));
  }
}
