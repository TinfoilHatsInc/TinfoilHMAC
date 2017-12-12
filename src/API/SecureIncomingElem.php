<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Util\ConfigReader;

abstract class SecureIncomingElem
{

  /**
   * @param array $securedComm
   * @return bool
   */
  protected static function validate(array $securedComm)
  {
    $sharedKey = ConfigReader::requireConfig ('sharedKey');

    if ( !empty($securedComm['body'])
      && !empty($securedComm['nonce'])
      && !empty($securedComm['hmac'])
      && strlen($securedComm['nonce']) == 40
      && strlen($securedComm['hmac']) == 64) {

      $nonce = $securedComm['nonce'];
      $hmac = $securedComm['hmac'];

      $hmacAlgo = ConfigReader::requireConfig ('hmacAlgorithm');

      $hmacKey = hash_hmac($hmacAlgo, $sharedKey, $nonce);

      $localHmac = hash_hmac($hmacAlgo, base64_encode(serialize([
        'body' => $securedComm['body'],
      ])), $hmacKey);

      return $localHmac == $hmac;

    }
    return FALSE;
  }

}