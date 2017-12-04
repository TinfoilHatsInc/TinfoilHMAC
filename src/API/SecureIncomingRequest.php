<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\ConfigReader;
use TinfoilHMAC\Exception\InvalidRequestException;
use TinfoilHMAC\Exception\MissingParameterException;

class SecureIncomingRequest
{

  private $httpMethod;
  private $apiMethod;
  private $params;

  /**
   * @return static
   * @throws InvalidRequestException
   * @throws MissingParameterException
   */
  public static function create()
  {
    $rawBody = file_get_contents('php://input');
    if (!empty($rawBody)) {
      $request = json_decode($rawBody, TRUE);
    } else {
      throw new InvalidRequestException('Request body is empty.');
    }
    if(!self::validateRequest($request)) {
      throw new InvalidRequestException('Invalid request.');
    }

    $self = new static();
    $self->httpMethod = strtolower($_SERVER['REQUEST_METHOD']);
    $self->apiMethod = $_GET['method'];
    $self->params = $request['params'];
    return $self;
  }

  private static function validateRequest(array $request)
  {
    $sharedKey = ConfigReader::requireConfig ('sharedKey');

    if ( !empty($_GET['method'])
      && !empty($request['params'])
      && !empty($request['nonce'])
      && !empty($request['hmac'])
      && strlen($request['nonce']) == 40
      && strlen($request['hmac']) == 64) {

      $nonce = $request['nonce'];
      $hmac = $request['hmac'];

      $hmacAlgo = ConfigReader::requireConfig ('hmacAlgorithm');

      $hmacKey = hash_hmac($hmacAlgo, $sharedKey, $nonce);

      $localHmac = hash_hmac($hmacAlgo, base64_encode(serialize([
        'params' => $request['params'],
      ])), $hmacKey);

      return $localHmac == $hmac;

    }
    return FALSE;
  }

  /**
   * @return mixed
   */
  public function getHttpMethod()
  {
    return $this->httpMethod;
  }

  /**
   * @return mixed
   */
  public function getApiMethod()
  {
    return $this->apiMethod;
  }

  /**
   * @return mixed
   */
  public function getParams()
  {
    return $this->params;
  }

}