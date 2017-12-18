<?php

namespace TinfoilHMAC\Util;

use TinfoilHMAC\Exception\MissingConfigException;
use TinfoilHMAC\Exception\MissingSharedKeyException;
use TinfoilHMAC\Exception\NoActiveSessionException;

class ClientSharedKey extends SharedKey
{

  public function getSharedKey()
  {
    try {
      return ConfigReader::requireConfig('sharedKey');
    } catch (MissingConfigException $e) {
      if (!UserSession::isSessionActive()) {
        throw new NoActiveSessionException('No active session.');
      }
      $sharedKey = $this->generateSharedKey();
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