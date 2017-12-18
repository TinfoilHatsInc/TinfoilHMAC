<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Util\Session;

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
    $keyGetter = Session::getInstance()->getSharedKey();
    if(!empty($keyGetter)) {
      $sharedKey = Session::getInstance()->getSharedKey();
    } else {
      $sharedKey = 'empty_key';
    }
    echo $this->getSecureBody($sharedKey);
  }

}