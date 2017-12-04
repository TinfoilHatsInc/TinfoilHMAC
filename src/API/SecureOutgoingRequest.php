<?php

namespace TinfoilHMAC\API;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use TinfoilHMAC\ConfigReader;

class SecureOutgoingRequest
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
   * @var mixed
   */
  private $params;

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
    $this->params = $params;
  }

  /**
   * @return Response
   */
  public function send()
  {
    $nonce = hash('sha1', rand());
    $sharedKey = ConfigReader::requireConfig ('sharedKey');
    $hmacAlgo = ConfigReader::requireConfig ('hmacAlgorithm');
    $hmacKey = hash_hmac($hmacAlgo, $sharedKey, $nonce);
    $body = [
      'params' => $this->params,
    ];
    $hmac = hash_hmac($hmacAlgo, base64_encode(serialize($body)), $hmacKey);
    $request = new Request($this->httpMethod, ConfigReader::requireConfig ('apiURL') . $this->apiMethod, [
      'content-type' => 'application/json',
    ], json_encode([
        'nonce' => $nonce,
        'hmac' => $hmac,
        'params' => $this->params,
      ]));
    $client = new Client();
    return $client->send($request);
  }

}