<?php

namespace TinfoilHMAC\Util;

use TinfoilHMAC\Exception\NoActiveSessionException;

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
    if($this->hasActiveSession()) {
      return $this->sharedKey->getSharedKey();
    } else {
      return hash('sha1', rand());
    }
  }

  public function initClientSharedKey() {
    if(!$this->hasActiveSession()) {
      $this->setSession(new ClientSharedKey());
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function hasKnownSharedKey() {
    $this->initClientSharedKey();
    try {
      $this->getSharedKey();
      return TRUE;
    } catch (NoActiveSessionException $e) {
      return FALSE;
    }
  }

  public function invalidateKnownSharedKey() {
    if($this->hasKnownSharedKey()) {
      ConfigReader::writeNewKey('');
    }
  }

}