<?php

namespace TinfoilHMAC\Util;

class Session
{

  /**
   * @var Session
   */
  private static $instance;
  /**
   * @var SharedKey
   */
  private $sharedKey;

  /**
   * @return $this
   */
  public static function getInstance() {
    if (empty(self::$instance)) {
      $class = get_called_class();
      self::$instance = new $class();
    }
    return self::$instance;
  }

  public function setSession(SharedKey $sharedKey) {
    $this->sharedKey = $sharedKey;
  }

  public function hasActiveSession() {
    return !empty($this->sharedKey);
  }

  public function getSharedKey() {
    return $this->sharedKey->getSharedKey();
  }

}