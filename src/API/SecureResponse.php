<?php

namespace TinfoilHMAC\API;

use GuzzleHttp\Psr7\Response;
use TinfoilHMAC\Exception\InvalidResponseException;

class SecureResponse extends SecureIncomingElem
{

  /**
   * @var int
   */
  private $responseCode;
  /**
   * @var array
   */
  private $message;
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
    $responseBody = json_decode($response->getBody()->getContents(), TRUE);
    if (!is_array($responseBody) || !self::validate($responseBody)) {
      throw new InvalidResponseException('Invalid response.');
    } else {
      $rawBody = $responseBody['body'];
      $this->message = $rawBody['message'];
      if (empty($rawBody['error'])) {
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
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * @return bool
   */
  public function hasError()
  {
    return $this->hasError;
  }

}