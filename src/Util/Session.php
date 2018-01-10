<?php

namespace TinfoilHMAC\Util;

use TinfoilHMAC\Exception\MissingSharedKeyException;
use TinfoilHMAC\Exception\NoActiveSessionException;

/**
 * Class Session
 * @package TinfoilHMAC\Util
 */
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

  /**
   * @param SharedKey $sharedKey
   * @return void
   */
  public function setSession(SharedKey $sharedKey) {
    $this->sharedKey = $sharedKey;
  }

  /**
   * @return bool
   */
  public function hasActiveSession() {
    return !empty($this->sharedKey);
  }

  /**
   * @param bool $errorOnInvalid
   * @throws MissingSharedKeyException
   * @return string
   */
  public function getSharedKey($errorOnInvalid = TRUE) {
    if($errorOnInvalid && !$this->sharedKeyIsValid()) {
      throw new MissingSharedKeyException('Shared key is invalid.');
    }
    if($this->hasActiveSession()) {
      return $this->sharedKey->getSharedKey();
    } else {
      return hash('sha1', rand());
    }
  }

  /**
   * @return bool
   */
  public function initClientSharedKey() {
    if(!$this->hasActiveSession()) {
      $this->setSession(new ClientSharedKey());
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
   * @return bool
   */
  public function hasKnownSharedKey() {
    $this->initClientSharedKey();
    try {
      $this->getSharedKey();
      return TRUE;
    } catch (NoActiveSessionException $e) {
      return FALSE;
    }
  }

  public function sharedKeyIsValid() {
    return !ConfigReader::requireConfig('sharedKeyInvalid');
  }

  /**
   * @return void
   */
  public function invalidateKnownSharedKey() {
    if($this->hasKnownSharedKey()) {
      ConfigReader::invalidateSharedKey();
    }
  }

}