<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Util\ConfigReader;
use TinfoilHMAC\Util\Session;

abstract class SecureIncomingElem
{

  /**
   * @param array $securedComm
   * @return bool
   */
  protected static function validate(array $securedComm)
  {
    $sharedKey = Session::getInstance()->getSharedKeyGetter()->getSharedKey();

    if ( isset($securedComm['body'])
      && !empty($securedComm['nonce'])
      && !empty($securedComm['hmac'])
      && strlen($securedComm['nonce']) == 40
      && strlen($securedComm['hmac']) == 64) {

      $nonce = $securedComm['nonce'];
      $hmac = $securedComm['hmac'];

      $hmacAlgo = ConfigReader::requireConfig('hmacAlgorithm');

      $hmacKey = hash_hmac($hmacAlgo, $sharedKey, $nonce);

      $localHmac = hash_hmac($hmacAlgo, base64_encode(serialize([
        'body' => $securedComm['body'],
      ])), $hmacKey);

      return $localHmac == $hmac;

    }
    return FALSE;
  }

}