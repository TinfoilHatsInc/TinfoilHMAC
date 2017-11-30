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
    $self->apiMethod = $request['method'];
    $self->params = $request['params'];
    return $self;
  }

  private static function validateRequest(array $request)
  {
    $sharedKey = ConfigReader::require ('sharedKey');

    if ( !empty($request['method'])
      && !empty($request['params'])
      && !empty($request['nonce'])
      && !empty($request['hmac'])
      && strlen($request['nonce']) == 40
      && strlen($request['hmac']) == 64) {

      $nonce = $request['nonce'];
      $hmac = $request['hmac'];

      $hmacKey = hash_hmac('sha256', $sharedKey, $nonce);

      $localHmac = hash_hmac('sha256', base64_encode(serialize([
        'method' => $request['method'],
        'params' => $request['params'],
      ])), $hmacKey);

      return $localHmac == $hmac;

    }
    return FALSE;
  }

}