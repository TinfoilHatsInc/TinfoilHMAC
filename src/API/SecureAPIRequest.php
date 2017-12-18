<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Exception\InvalidRequestException;
use TinfoilHMAC\Util\SharedKeyGetter;

class SecureAPIRequest extends SecureIncomingElem
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
   * @var array
   */
  private $params;

  /**
   * SecureIncomingRequest constructor.
   * @param SharedKeyGetter $sharedKeyGetter
   * @throws InvalidRequestException
   */
  public function __construct(SharedKeyGetter $sharedKeyGetter)
  {
    $rawBody = file_get_contents('php://input');
    if (!empty($rawBody)) {
      $request = json_decode($rawBody, TRUE);
    } else {
      throw new InvalidRequestException('Request body is empty.');
    }
    if(!empty($request['chubid']) && !empty($_GET['method'])
      && !self::validate(new $sharedKeyGetter($request['chubid']), $request)) {
      throw new InvalidRequestException('Invalid request.');
    } else {
      $this->httpMethod = strtolower($_SERVER['REQUEST_METHOD']);
      $this->apiMethod = $_GET['method'];
      $this->params = $request['body'];
    }
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