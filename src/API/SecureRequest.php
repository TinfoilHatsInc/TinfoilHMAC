<?php

namespace TinfoilHMAC\API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use TinfoilHMAC\Exception\MissingConfigException;
use TinfoilHMAC\Exception\MissingSharedKeyException;
use TinfoilHMAC\Util\ClientSharedKey;
use TinfoilHMAC\Util\ConfigReader;
use TinfoilHMAC\Util\Session;

class SecureRequest extends SecureOutgoingElem
{

  /**
   * @var string
   */
  private $httpMethod;
  /**
   * @var string
   */
  private $apiMethod;

  /**
   * SecureRequest constructor.
   * @param string $httpMethod
   * @param string $apiMethod
   * @param $params
   */
  public function __construct($httpMethod = 'GET', $apiMethod, $params)
  {
    if(!Session::getInstance()->hasActiveSession()) {
      Session::getInstance()->setSession(new ClientSharedKey());
    }
    $this->httpMethod = $httpMethod;
    $this->apiMethod = $apiMethod;
    parent::__construct($params);
  }

  /**
   * @return SecureResponse
   */
  public function send()
  {
    $new = FALSE;
    try {
      $sharedKey = Session::getInstance()->getSharedKey();
    } catch (MissingSharedKeyException $e) {
      $sharedKey = $e->getNewSharedKey();
      $new = TRUE;
    }
    $body = $this->getSecureBody($sharedKey, $new);
    $request = new Request($this->httpMethod, ConfigReader::requireConfig('apiURL') . $this->apiMethod, [
      'content-type' => 'application/json',
    ], $body);
    $client = new Client();
    try {
      $response = $client->send($request);
    } catch (ClientException $e) {
      $response = $e->getResponse();
    } catch (ServerException $e) {
      $response = $e->getResponse();
    }
    return new SecureResponse($response);
  }


}