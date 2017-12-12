<?php

namespace TinfoilHMAC\API;

use GuzzleHttp\Psr7\Response;
use TinfoilHMAC\Exception\InvalidResponseException;

class SecureIncomingResponse extends SecureIncomingElem
{

  /**
   * @var int
   */
  private $responseCode;
  /**
   * @var array
   */
  private $responseBody;
  /**
   * @var bool
   */
  private $hasError = TRUE;

  /**
   * SecureIncomingResponse constructor.
   * @param Response $response
   * @throws InvalidResponseException
   */
  public function __construct(Response $response)
  {
    $responseBody = json_decode($response->getBody()->getContents());
    if (!self::validate($responseBody)) {
      throw new InvalidResponseException('Invalid response.');
    } else {
      $rawBody = $responseBody['body'];
      $this->responseBody = $rawBody;
      if (empty($responseBody['error'])) {
        $this->hasError = FALSE;
      }
      $this->responseCode = $response->getStatusCode();
    }
  }

  /**
   * @return mixed
   */
  public function getResponseCode()
  {
    return $this->responseCode;
  }

  /**
   * @return mixed
   */
  public function getResponseBody()
  {
    return $this->responseBody;
  }

  /**
   * @return bool
   */
  public function hasError()
  {
    return $this->hasError;
  }

}