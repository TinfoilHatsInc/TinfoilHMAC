<?php

namespace TinfoilHMAC\Util;

class UserSession
{

  public static function open($email, $password)
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_name('sid');
      $secureCookie = FALSE;
      if (self::sessionIsSecure()) {
        $secureCookie = TRUE;
      }
      session_set_cookie_params(0, '/', NULL, $secureCookie, TRUE);
      ini_set('session.hash_function', 'sha512');
      ini_set('session.sid_length', '128');
      ini_set('session.use_strict_mode', '1');
      session_start();
    }
    $_SESSION['hapi'] = [
      'email' => $email,
      'password' => CredentialSecurity::hashUserPassword($email, $password),
    ];
  }

  public static function destroy()
  {
    session_destroy();
    session_regenerate_id();
  }

  public static function sessionIsSecure()
  {
    return
      (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || $_SERVER['SERVER_PORT'] == 443;
  }

  public static function getUserEmail() {
    return $_SESSION['email'];
  }

  public static function getUserPassword() {
    return $_SESSION['password'];
  }

  public static function isSessionActive() {
    return !empty($_SESSION['email']) || !empty($_SESSION['password']);
  }

}