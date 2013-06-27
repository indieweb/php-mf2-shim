<?php
namespace mf2\Shim;

use mf2\Shim;

class Twitter extends Shim {

  // TODO: parse() method that returns an 'items' array that corresponds to if twitter.com
  // had proper microformats markup

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
      
#      var_dump($tweetText);

      $tags = array();

      $item['type'] = array('h-entry');
      $item['properties'] = array(
        'name' => array($tweetText),
				'content' => array($tweetText), // TODO: this should be C14N of node children
        'summary' => array($tweetText),
        'author' => array(
          array(
            'type' => array('h-card'),
            'properties' => array(
              'name' => array($authorName),
              'nickname' => array($authorNickname),
              'photo' => array($authorPhoto),
              'url' => array($authorURL)
            )
          )
        ),
        'url' => '',
        'published' => '',
        'category' => $tags
      );


      $items[] = $item;
    }

    return array('items' => array_values(array_filter($items)));
  }
}
