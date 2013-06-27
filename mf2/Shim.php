<?php
namespace mf2;

use DOMDocument,
  DOMElement,
  DOMXPath,
  DOMNode,
  DOMNodeList,
  DateTime,
  Exception;

use Symfony\Component\CssSelector\CssSelector;

class Shim {

  /** @var string The baseurl (if any) to use for this parse */
  public $baseurl;

  /** @var DOMXPath object which can be used to query over any fragment*/
  protected $xpath;
  
  /** @var bool Whether or not to output datetimes as strings */
  public $stringDateTimes = false;
  
  /** @var SplObjectStorage */
  private $parsed;
  
  /** @var DOMDocument */
  private $doc;

  public function __construct($input, $baseurl = null) {
    // For the moment: assume string = string of HTML
    if (is_string($input)) {
      if (strtolower(mb_detect_encoding($input)) == 'utf-8') {
        $input = mb_convert_encoding($input, 'HTML-ENTITIES', "UTF-8");
      }

      $doc = new DOMDocument();
      @$doc->loadHTML($input);
    } elseif (is_a($input, 'DOMDocument')) {
      $doc = $input;
    } else {
      // TODO: should we throw an exception here?
      $doc = new DOMDocument();
      @$doc->loadHTML('');
    }
    
    $this->xpath = new DOMXPath($doc);
    
    foreach ($this->xpath->query('//base[@href]') as $base) {
      $baseElementUrl = $base->getAttribute('href');
      
      if (parse_url($baseElementUrl, PHP_URL_SCHEME) === null) {
        /* The base element URL is relative to the document URL.
         *
         * :/
         *
         * Perhaps the author was high? */
        
        $deriver = new AbsoluteUrlDeriver($baseElementUrl, $baseurl);
        $baseurl = (string) $deriver->getAbsoluteUrl(); 
      } else {
        $baseurl = $baseElementUrl;
      }
      break;
    }
    
    $this->baseurl = $baseurl;
    
    $this->doc = $doc;

    $this->parsed = new \SplObjectStorage();

  }

  public function cssQuery($selector) {
    return $this->xpath->query(CssSelector::toXPath($selector));
  }

  // retrieve a single value given a CSS selector
	// TODO: why is this returning the nodeValue?
  public function single($selector) {
    foreach($this->cssQuery($selector) as $n){
      return $n->nodeValue;
    }
		
    return null;
  }
	
	public function querySelector($selector) {
		foreach ($this->cssQuery($selector) as $n) {
			return $n;
		}
		
		return null;
	}
	
	public function xpath($selector, $context = null) {
		return $this->xpath->query($selector, $context);
	}
}
