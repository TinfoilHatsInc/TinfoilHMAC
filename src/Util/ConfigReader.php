<?php

namespace TinfoilHMAC\Util;

use Exception;
use Symfony\Component\Yaml\Yaml;

class ConfigReader
{

  private static $config;

  /**
   * @return mixed
   * @throws Exception
   */
  private static function getConfig()
  {

    if (empty(self::$config)) {
      $filePath = __DIR__ . '/../config.yml';
      if (!file_exists($filePath)) {
        throw new Exception('Config file could not be found.');
      }
      self::$config = Yaml::parse(file_get_contents($filePath));
    }
    return self::$config;

  }

  /**
   * @param $key
   * @return string|array
   * @throws Exception
   */
  public static function requireConfig($key)
  {

    $keys = $key;
    if (!is_array($key)) {
      $keys = [$key];
    }

    $config = self::getConfig();

    $keyValues = [];
    $missing = [];
    foreach ($keys as $key) {
      if (!array_key_exists($key, $config) || $config[$key] == '') {
        $missing[] = '\'' . $key . '\'';
      } else {
        $keyValues[$key] = $config[$key];
      }
    }

    if (!empty($missing)) {
      throw new Exception('Config file does not have ' . implode(', ', $missing) . ' defined.');
    } else {
      if (!is_array($key)) {
        return $config[$key];
      } else {
        return $keyValues;
      }
    }

  }

}