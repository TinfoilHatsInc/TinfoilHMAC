<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Util\Session;
use TinfoilHMAC\Util\SharedKeyGetter;

class SecureAPIResponse extends SecureOutgoingElem
{

  /**
   * @var int
   */
  private $responseCode;

  public function __construct($responseCode, array $body)
  {
    $this->responseCode = $responseCode;
    parent::__construct($body);
  }

  public function send()
  {
    http_response_code($this->responseCode);
    echo $this->getSecureBody(Session::getInstance()->getSharedKeyGetter()->getSharedKey());
  }

}