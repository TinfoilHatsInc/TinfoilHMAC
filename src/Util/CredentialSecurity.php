<?php

namespace TinfoilHMAC\Util;

/**
 * Class CredentialSecurity
 * @package TinfoilHMAC\Util
 */
class CredentialSecurity
{

  /**
   * @param string $email
   * @param string $password
   * @return string
   */
  public static function hashUserPassword($email, $password)
  {
    return hash('sha256', $password . $email);
  }

}