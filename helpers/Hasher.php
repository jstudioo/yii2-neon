<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace lambda\neon\helpers;

/**
 * Description of Hasher
 *
 * @author lambdaconsulting
 */
class Hasher {

  /**
   * @author Haqqi <haqqi@7trees.co>
   * @var \Hashids\Hashids $hashid
   */
  private static $hashid = false;

  /**
   * To prevent object construction from external
   *
   * @author Haqqi <haqqi@7trees.co>
   * @param type $config
   */
  private function __construct($config = array()) {
    parent::__construct($config);
  }

  /**
   * @author Haqqi <haqqi@7trees.co>
   */
  private static function initHash() {
    if (self::$hashid === false) {
      self::$hashid = new \Hashids\Hashids('aes-crm', 7);
    }
  }

  /**
   * @author Haqqi <haqqi@7trees.co>
   * @param integer $id Numeric value that want to be encoded
   * @return string Encoded string
   */
  public static function encode($id) {
    self::initHash();
    return self::$hashid->encode($id);
  }

  /**
   * @author Haqqi <haqqi@7trees.co>
   * @param string $hashid
   * @return integer The decoded value
   */
  public static function decode($hashId) {
    self::initHash();
    return self::$hashid->decode($hashId);
  }

  public static function stringDecode($hashId) {
    self::initHash();
    $value = self::$hashid->decode($hashId);
    return array_shift($value);
  }

}
