<?php

namespace TinfoilHMAC\Util;

use Exception;
use Symfony\Component\Yaml\Yaml;
use TinfoilHMAC\Exception\MissingConfigException;

/**
 * Class ConfigReader
 * @package TinfoilHMAC\Util
 */
class ConfigReader{

  const CONFIG_FILE_LOCATION = '/../../config.yml';

  /**
   * @var array
   */
  private static $config;

  /**
   * @return string
   * @throws Exception
   */
  private static function getConfigFile() {
    $configFile = __DIR__ . self::CONFIG_FILE_LOCATION;
    if (!file_exists($configFile)) {
      throw new Exception('Config file could not be found.');
    } else {
      return $configFile;
    }
  }

  /**
   * @return array
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
   * @param bool $errorOnEmpty
   * @return string|array
   * @throws Exception
   */
  public static function requireConfig($key, $errorOnEmpty = TRUE)
  {

    $keys = $key;
    if (!is_array($key)) {
      $keys = [$key];
    }

    $config = self::getConfig();

    $keyValues = [];
    $missing = [];
    foreach ($keys as $key) {
      if (!array_key_exists($key, $config) || ($errorOnEmpty && $config[$key] == '')) {
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

  /**
   * @param $value
   * @return void
   */
  public static function writeNewKey($value){
    $currentValsYaml = self::getConfig();
    $currentValsYaml['sharedKey'] = $value;
    $currentValsYaml['sharedKeyInvalid'] = FALSE;
    $newYaml = Yaml::dump($currentValsYaml);
    self::writeConfig($newYaml);
  }

  /**
   * @param $contents
   * @return void
   */
  private static function writeConfig($contents){
    file_put_contents(self::getConfigFile(), $contents);
    self::$config = Yaml::parse($contents);
  }

  public static function invalidateSharedKey() {
    $config = self::getConfig();
    $config['sharedKeyInvalid'] = TRUE;
    $newConfig = Yaml::dump($config);
    self::writeConfig($newConfig);
  }

  public static function writeSessionInitTime() {
    $config = self::$config;
    $config['sessInitTime'] = (new \DateTime())->format('Y-m-d H:i:s');
    $newConfig = Yaml::dump($config);
    self::writeConfig($newConfig);
  }

}