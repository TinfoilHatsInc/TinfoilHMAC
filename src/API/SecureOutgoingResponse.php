<?php

namespace TinfoilHMAC\API;

class SecureOutgoingResponse extends SecureOutgoingElem
{

  private $responseCode;

  public function __construct($responseCode, array $body)
  {
    $this->responseCode;
    parent::__construct($body);
  }

  public function send()
  {
    http_response_code($this->responseCode);
    echo json_encode($this->getSecureBody());
  }

}