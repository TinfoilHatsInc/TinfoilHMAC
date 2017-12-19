<?php

namespace TinfoilHMAC\Util;

class CredentialSecurity
{

  public static function hashUserPassword($email, $password)
  {
    return hash('sha256', $password . $email);
  }

}