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
  public static function getInstance()
  {
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
  public function setSession(SharedKey $sharedKey)
  {
    $this->sharedKey = $sharedKey;
  }

  /**
   * @return bool
   */
  public function hasActiveSession()
  {
    return !empty($this->sharedKey);
  }

  /**
   * @param $ignoreInvalidSK bool
   * @return string
   */
  public function getSharedKey($ignoreInvalidSK = FALSE)
  {
    if ($this->hasActiveSession()) {
      return $this->sharedKey->getSharedKey($ignoreInvalidSK);
    } else {
      return hash('sha1', rand());
    }
  }

  /**
   * @return bool
   */
  public function initClientSharedKey()
  {
    if (!$this->hasActiveSession()) {
      $this->setSession(new ClientSharedKey());
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
   * @return bool
   */
  public function hasKnownSharedKey()
  {
    $this->initClientSharedKey();
    try {
      $this->getSharedKey();
      return TRUE;
    } catch (NoActiveSessionException $e) {
      return FALSE;
    }
  }

  public function sharedKeyIsValid()
  {
    return !ConfigReader::requireConfig('sharedKeyInvalid', FALSE);
  }

  /**
   * @return void
   */
  public function invalidateKnownSharedKey()
  {
    ConfigReader::invalidateSharedKey();
  }

  public function getSetKey() {
    return $this->sharedKey->getSetKey();
  }

}