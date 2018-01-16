<?php

namespace TinfoilHMAC\Util;

use TinfoilHMAC\Exception\ActiveSessionException;

/**
 * Class UserSession
 * @package TinfoilHMAC\Util
 */
class UserSession
{

  /**
   * @param string $email
   * @param string $password
   * @param bool $replace
   * @throws ActiveSessionException
   * @return void
   */
  public static function open($email, $password, $replace = FALSE)
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_name('sid');
      $secureCookie = FALSE;
      if (self::sessionIsSecure()) {
        $secureCookie = TRUE;
      }
      session_set_cookie_params(0, '/', NULL, $secureCookie, TRUE);
      session_start([
        'sid_length' => 128,
        'use_strict_mode' => TRUE,
      ]);
    } elseif (array_key_exists('hapi', $_SESSION) && !$replace) {
      throw new ActiveSessionException('A session is already active.');
    }
    $_SESSION['hapi'] = [
      'email' => $email,
      'password' => CredentialSecurity::hashUserPassword($email, $password),
    ];
  }

  /**
   * @return void
   */
  public static function destroy()
  {
    unset($_SESSION['hapi']);
    session_regenerate_id();
  }

  /**
   * @return bool
   */
  public static function sessionIsSecure()
  {
    return
      (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || $_SERVER['SERVER_PORT'] == 443;
  }

  /**
   * @return string
   */
  public static function getUserEmail() {
    return $_SESSION['hapi']['email'];
  }

  /**
   * @return string
   */
  public static function getUserPassword() {
    return $_SESSION['hapi']['password'];
  }

  /**
   * @return bool
   */
  public static function isSessionActive() {
    return !empty($_SESSION['hapi']['email']) && !empty($_SESSION['hapi']['password']);
  }

  public static function isSessionValid() {
    $configReader = new ConfigReader();
    $sessInitTime = $configReader->requireConfig('sessInitTime', FALSE);
    if(empty($sessInitTime)) {
      return FALSE;
    } else {
      $now = strtotime('now');
      $time = strtotime('+1 day', strtotime($sessInitTime));
      return $time >= $now;
    }
  }

}