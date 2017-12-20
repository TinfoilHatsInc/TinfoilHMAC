<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Util\Session;

/**
 * Class SecureAPIResponse
 * @package TinfoilHMAC\API
 */
class SecureAPIResponse extends SecureOutgoingElem
{

  /**
   * @var int
   */
  private $responseCode;

  /**
   * SecureAPIResponse constructor.
   * @param $responseCode
   * @param array $body
   */
  public function __construct($responseCode, array $body)
  {
    $this->responseCode = $responseCode;
    parent::__construct($body);
  }

  /**
   * @return void
   */
  public function send()
  {
    http_response_code($this->responseCode);
    $sharedKey = Session::getInstance()->getSharedKey();
    echo json_encode($this->getSecureBody($sharedKey));
  }

}