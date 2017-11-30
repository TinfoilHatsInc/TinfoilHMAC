<?php

namespace TinfoilHMAC\Security;

use TinfoilHMAC\ConfigReader;

class SecureRequestHandler
{

  public function validateSSOToken()
  {

    $sharedKey = ConfigReader::require ('sharedKey');

    $isValid = false;

    if (
      isset($_GET['nonce'])
      && isset($_GET['hmac'])
      && strlen($_GET['nonce']) == 40
      && strlen($_GET['hmac']) == 64
    ) {

      $nonce = $_GET['nonce'];
      $hmac = $_GET['hmac'];

      // Sign the key with the nonce to get the tmp key
      $hmacKey = hash_hmac('sha256', $sharedKey, $nonce);

      // rebuild the string to sign
      $localHmac = hash_hmac('sha256', $userId, $hmacKey);

      // Compare against the incoming key
      $isValid = $localHmac == $hmac ? true : false;

    }
    return $isValid;
  }

}