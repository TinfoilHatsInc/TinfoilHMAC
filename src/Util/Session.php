<?php

namespace TinfoilHMAC\Util;

abstract class Session
{

  /**
   * @var Session
   */
  private static $instance;
  /**
   * @var SharedKeyGetter
   */
  private $sharedKeyGetter;

  /**
   * @return $this
   */
  public static function getInstance() {
    if (empty(self::$instance)) {
      self::$instance = new static();
    }
    return self::$instance;
  }

  public function setSession(SharedKeyGetter $sharedKeyGetter) {
    $this->sharedKeyGetter = $sharedKeyGetter;
  }

  public function getSharedKeyGetter() {
    return $this->sharedKeyGetter;
  }

}