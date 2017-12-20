<?php

namespace TinfoilHMAC\Exception;

use Exception;

/**
 * Class MissingSharedKeyException
 * @package TinfoilHMAC\Exception
 */
class MissingSharedKeyException extends Exception
{

  /**
   * @var string
   */
  private $sharedKey;

  /**
   * @param $newSharedKey string
   */
  public function setNewSharedKey($newSharedKey)
  {
    $this->sharedKey = $newSharedKey;
  }

  /**
   * @return string
   */
  public function getNewSharedKey()
  {
    return $this->sharedKey;
  }

}