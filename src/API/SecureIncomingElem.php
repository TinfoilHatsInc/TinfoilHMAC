<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Exception\InvalidHMACException;
use TinfoilHMAC\Exception\InvalidResponseException;
use TinfoilHMAC\Util\ConfigReader;
use TinfoilHMAC\Util\Session;

/**
 * Class SecureIncomingElem
 * @package TinfoilHMAC\API
 */
abstract class SecureIncomingElem
{

  /**
   * @param array $securedComm
   * @return bool
   * @throws InvalidHMACException
   * @throws InvalidResponseException
   */
  protected static function validate(array $securedComm)
  {
    $sharedKey = Session::getInstance()->getSharedKey();

    if (isset($securedComm['body'])
      && !empty($securedComm['nonce'])
      && !empty($securedComm['hmac'])
      && strlen($securedComm['nonce']) == 40
      && strlen($securedComm['hmac']) == 64
    ) {

      $nonce = $securedComm['nonce'];
      $hmac = $securedComm['hmac'];

      $hmacAlgo = ConfigReader::requireConfig('hmacAlgorithm');

      $hmacKey = hash_hmac($hmacAlgo, $sharedKey, $nonce);

      $localHmac = hash_hmac($hmacAlgo, base64_encode(serialize([
        'body' => $securedComm['body'],
      ])), $hmacKey);

      if ($localHmac == $hmac) {
        return TRUE;
      } else {
        if (!empty($securedComm['body']['message']) && !empty($securedComm['body']['error'])) {
          throw new InvalidHMACException('The HMAC was invalid. Message from API: ' . $securedComm['body']['message']);
        } else {
          throw new InvalidHMACException('The HMAC was invalid.');
        }
      }
    }
    return FALSE;
  }

}