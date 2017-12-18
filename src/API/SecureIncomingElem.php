<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Util\ConfigReader;
use TinfoilHMAC\Util\SharedKeyGetter;

abstract class SecureIncomingElem
{

  /**
   * @param SharedKeyGetter $sharedKeyGetter
   * @param array $securedComm
   * @return bool
   */
  protected static function validate(SharedKeyGetter $sharedKeyGetter, array $securedComm)
  {
    $sharedKey = $sharedKeyGetter->getSharedKey();

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