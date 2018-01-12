<?php

namespace TinfoilHMAC\Util;

use TinfoilHMAC\Exception\MissingConfigException;
use TinfoilHMAC\Exception\MissingSharedKeyException;
use TinfoilHMAC\Exception\NoActiveSessionException;

/**
 * Class ClientSharedKey
 * @package TinfoilHMAC\Util
 */
class ClientSharedKey extends SharedKey
{

  /**
   * @var string
   */
  private $sharedKey;

  /**
   * @return array|string
   * @throws MissingSharedKeyException
   * @throws NoActiveSessionException
   */
  public function getSharedKey()
  {
    if(empty($this->sharedKey)) {
      try {
        if(!Session::getInstance()->sharedKeyIsValid()) {
          throw new MissingConfigException('Shared key invalid.');
        }
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

  public function getSetKey()
  {
    return ConfigReader::requireConfig('setKey');
  }

}