<?php

namespace TinfoilHMAC\API;

use TinfoilHMAC\Exception\InvalidRequestException;
use TinfoilHMAC\Exception\InvalidSessionParamException;
use TinfoilHMAC\Util\Session;

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
   * SecureAPIRequest constructor.
   * @param $sharedKeyGetterClass
   * @throws InvalidRequestException
   * @throws InvalidSessionParamException
   */
  public function __construct($sharedKeyGetterClass)
  {
    $rawBody = file_get_contents('php://input');
    if (!empty($rawBody)) {
      $request = json_decode($rawBody, TRUE);
    } else {
      throw new InvalidSessionParamException('Request body is empty.');
    }
    if(empty($request['body'])) {
      throw new InvalidSessionParamException('Request body is empty.');
    }
    $body = $request['body'];
    if(!empty($body['chubId'])) {
      Session::getInstance()->setSession(new $sharedKeyGetterClass($body['chubId']));
    } else {
      throw new InvalidSessionParamException('Invalid request.');
    }
    if(!empty($body['chubId']) && !empty($_GET['method'])
      && !self::validate($request)) {
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