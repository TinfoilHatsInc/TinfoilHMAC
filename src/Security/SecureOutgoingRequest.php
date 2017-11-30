<?php

namespace TinfoilHMAC\Security;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
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
   * @var Client
   */
  private $client;

  /**
   * SecureRequest constructor.
   * @param string $httpMethod
   * @param string $apiMethod
   * @param $params
   */
  public function __construct($httpMethod = Request::GET, $apiMethod, $params)
  {
    $this->httpMethod = $httpMethod;
    $this->apiMethod = $apiMethod;
    $this->params = $params;
    $this->client = new Client(ConfigReader::require ('apiURL'));
  }

  /**
   * @return \Guzzle\Http\Message\Response
   */
  public function send()
  {
    $nonce = hash('sha1', rand());
    $sharedKey = ConfigReader::require ('sharedKey');
    $hmacAlgo = ConfigReader::require ('hmacAlgorithm');
    $hmacKey = hash_hmac($hmacAlgo, $sharedKey, $nonce);
    $body = [
      'method' => $this->apiMethod,
      'params' => $this->params,
    ];
    $hmac = hash_hmac($hmacAlgo, base64_encode(serialize($body)), $nonce);
    $request = $this->client->createRequest($this->httpMethod, null, [
      'content-type' => 'application/json',
    ], [
      'json' => json_encode([
        'nonce' => $nonce,
        'sharedKey' => $sharedKey,
        'hmacKey' => $hmacKey,
        'hmac' => $hmac,
        'method' => $this->apiMethod,
        'params' => $this->params,
      ])
    ]);
    return $this->client->send($request);
  }


}