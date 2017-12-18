<?php

namespace TinfoilHMAC\Util;

use Exception;
use Symfony\Component\Yaml\Yaml;
use TinfoilHMAC\Exception\MissingConfigException;

class ConfigReader{

  const CONFIG_FILE_LOCATION = __DIR__ . '/../../config.yml';

  private static $config;

  private static function getConfigFile() {
    if (!file_exists(self::CONFIG_FILE_LOCATION)) {
      throw new Exception('Config file could not be found.');
    } else {
      return self::CONFIG_FILE_LOCATION;
    }
  }

  /**
   * @return mixed
   * @throws Exception
   */
  private static function getConfig()
  {
    if (empty(self::$config)) {
      self::$config = Yaml::parse(file_get_contents(self::getConfigFile()));
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
      throw new MissingConfigException('Config file does not have ' . implode(', ', $missing) . ' defined.');
    } else {
      if (!is_array($key)) {
        return $config[$key];
      } else {
        return $keyValues;
      }
    }

  }

  public static function writeNewKey($value){
    $currentValsYaml = self::getConfig();
    $currentValsYaml['sharedKey'] = $value;
    $newYaml = Yaml::dump($currentValsYaml);
    self::writeConfig($newYaml);
  }

  public static function writeConfig($contents){
    file_put_contents(self::getConfigFile(), $contents);
    self::$config = Yaml::parse($contents);
  }

}