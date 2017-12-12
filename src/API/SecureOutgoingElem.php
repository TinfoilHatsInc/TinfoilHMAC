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

  protected function getSecureBody() {
    $nonce = hash('sha1', rand());
    $sharedKey = ConfigReader::requireConfig ('sharedKey');
    $hmacAlgo = ConfigReader::requireConfig ('hmacAlgorithm');
    $hmacKey = hash_hmac($hmacAlgo, $sharedKey, $nonce);
    $body = [
      'body' => $this->body,
    ];
    $hmac = hash_hmac($hmacAlgo, base64_encode(serialize($body)), $hmacKey);
    return json_encode([
      'nonce' => $nonce,
      'hmac' => $hmac,
      'body' => $this->body,
    ]);
  }

  public abstract function send();

}