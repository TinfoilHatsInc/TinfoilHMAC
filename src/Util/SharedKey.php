<?php

namespace TinfoilHMAC\Util;

/**
 * Class SharedKey
 * @package TinfoilHMAC\Util
 */
abstract class SharedKey
{

  /**
   * @return string
   */
  public abstract function getSharedKey();

}