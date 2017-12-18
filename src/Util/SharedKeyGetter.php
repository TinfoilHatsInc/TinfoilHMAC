<?php

namespace TinfoilHMAC\Util;

abstract class SharedKeyGetter
{

  /**
   * @var string
   */
  private $chubId;

  /**
   * SharedKeyGetter constructor.
   * @param $chubId string
   */
  public function __construct($chubId)
  {
    $this->chubId = $chubId;
  }

  /**
   * @return string
   */
  public function getChubId() {
    return $this->chubId;
  }

  public abstract function getSharedKey();

}