<?php

namespace TinfoilHMAC\Exception;

use Exception;

class MissingSharedKeyException extends Exception
{

  private $sharedKey;

  public function setNewSharedKey($newSharedKey)
  {
    $this->sharedKey = $newSharedKey;
  }

  public function getNewSharedKey()
  {
    return $this->sharedKey;
  }

}