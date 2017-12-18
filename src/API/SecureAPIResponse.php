<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Util\SharedKeyGetter;

class SecureAPIResponse extends SecureOutgoingElem
{

  /**
   * @var int
   */
  private $responseCode;
  /**
   * @var SharedKeyGetter
   */
  private $sharedKeyGetter;

  public function __construct(SharedKeyGetter $sharedKeyGetter, $responseCode, array $body)
  {
    $this->sharedKeyGetter = $sharedKeyGetter;
    $this->responseCode = $responseCode;
    parent::__construct($body);
  }

  public function send()
  {
    http_response_code($this->responseCode);
    echo $this->getSecureBody($this->sharedKeyGetter->getSharedKey());
  }

}