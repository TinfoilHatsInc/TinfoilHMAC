<?php

namespace TinfoilHMAC\API;

class SecureAPIResponse extends SecureOutgoingElem
{

  private $responseCode;

  public function __construct($responseCode, array $body)
  {
    $this->responseCode = $responseCode;
    parent::__construct($body);
  }

  public function send()
  {
    http_response_code($this->responseCode);
    echo $this->getSecureBody();
  }

}