<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Util\ConfigReader;
use TinfoilHMAC\Util\UserSession;

/**
 * Class SecureOutgoingElem
 * @package TinfoilHMAC\API
 */
abstract class SecureOutgoingElem
{

  /**
   * @var array
   */
  private $body;

  /**
   * SecureOutgoingElem constructor.
   * @param array $body
   */
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
      $email = UserSession::getUserEmail();
      $password = UserSession::getUserPassword();
      $this->body['email'] = $email;
      $this->body['password'] = $password;
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

  /**
   * @return void
   */
  public abstract function send();

}