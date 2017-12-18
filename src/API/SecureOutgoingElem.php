<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Util\ConfigReader;

abstract class SecureOutgoingElem
{

  private $body;

  public function __construct($body)
  {
    $this->body = $body;
  }

  /**
   * @param $sharedKey
   * @param bool $newKey
   * @return array
   */
  protected function getSecureBody($sharedKey, $newKey = FALSE) {

    $nonce = hash('sha1', rand());
    if($newKey) {
      $this->body['key'] = $sharedKey;
    }
    $hmacAlgo = ConfigReader::requireConfig ('hmacAlgorithm');
    $hmacKey = hash_hmac($hmacAlgo, $sharedKey, $nonce);
    $body = [
      'body' => $this->body,
    ];
    $hmac = hash_hmac($hmacAlgo, base64_encode(serialize($body)), $hmacKey);
    return [
      'nonce' => $nonce,
      'hmac' => $hmac,
      'body' => $this->body,
    ];
  }

  public abstract function send();

}