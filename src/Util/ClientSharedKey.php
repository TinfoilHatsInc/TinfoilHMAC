<?php

namespace TinfoilHMAC\Util;

use TinfoilHMAC\Exception\MissingConfigException;
use TinfoilHMAC\Exception\MissingSharedKeyException;
use TinfoilHMAC\Exception\NoActiveSessionException;

class ClientSharedKey extends SharedKey
{

  private $sharedKey;

  public function getSharedKey()
  {
    if(empty($this->sharedKey)) {
      try {
        $this->sharedKey = ConfigReader::requireConfig('sharedKey');
      } catch (MissingConfigException $e) {
        if (!UserSession::isSessionActive()) {
          throw new NoActiveSessionException('No active session.');
        }
        $sharedKey = $this->generateSharedKey();
        $this->sharedKey = $sharedKey;
        $exception = new MissingSharedKeyException();
        $exception->setNewSharedKey($sharedKey);
        throw $exception;
      }
    }
    return $this->sharedKey;
  }

  /**
   * @return string
   */
  private function generateSharedKey() {
    return hash('sha256', random_bytes(32));
  }

}