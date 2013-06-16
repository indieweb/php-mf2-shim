<?php
namespace mf2\Shim;

use mf2\Shim;

class Instagram extends Shim {

  // TODO: parse() method that returns an 'items' array that corresponds to if instagram.com
  // had proper microformats markup

  /**
   * Kicks off the parsing routine
   * 
   * @return array An array containing all the µfs found in the current document
   */
  public function parse() {
    $mfs = array();

    return array('items' => array_values(array_filter($mfs)));
  }


}
