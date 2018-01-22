<?php

namespace TinfoilHMAC\Util;

/**
 * Class SharedKey
 * @package TinfoilHMAC\Util
 */
abstract class SharedKey
{

  /**
   * @param $ignoreInvalidSK bool
   * @return string
   */
  public abstract function getSharedKey($ignoreInvalidSK = FALSE);

  public abstract function getSetKey();

}