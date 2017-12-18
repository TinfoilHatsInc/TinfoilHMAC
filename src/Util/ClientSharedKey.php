<?php

namespace TinfoilHMAC\Util;

use TinfoilHMAC\Exception\MissingConfigException;
use TinfoilHMAC\Exception\MissingSharedKeyException;

class ClientSharedKey extends SharedKey
{

  public function getSharedKey()
  {
    try {
      return ConfigReader::requireConfig('sharedKey');
    } catch (MissingConfigException $e) {
      $sharedKey = $this->generateSharedKey();
      ConfigReader::writeConfig('sharedKey', $sharedKey);
      $exception = new MissingSharedKeyException();
      $exception->setNewSharedKey($sharedKey);
      throw $exception;
    }
  }

  /**
   * @return string
   */
  private function generateSharedKey() {
    return hash('sha256', random_bytes(32));
  }

}