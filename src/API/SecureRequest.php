<?php

namespace TinfoilHMAC\API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use TinfoilHMAC\Util\ConfigReader;

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
    $this->httpMethod = $httpMethod;
    $this->apiMethod = $apiMethod;
    parent::__construct($params);
  }

  /**
   * @return SecureResponse
   */
  public function send()
  {
    $body = $this->getSecureBody();
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