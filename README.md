TinfoilHMAC library
===================

This library is used to send secure requests to a registered API (Tinfoil HAPI) and handle incoming secure request.

## Library explaination

The following classes in this library can be used:
* SecureAPIRequest
* SecureAPIResponse
* **SecureRequest**
* **SecureResponse**

The first two classes are **not** be used in clients as they are only applicable in APIs (Tinfoil HAPI) for processing 
incoming requests from clients using this library and sending responses.necessaryreRequest class can be used to send secure requests to the registered API and will, if a *valid response is 
returned, create a SecureResponse object containing the HTTP response code, a response message and a boolean 
representing the success of the request.

\* For a response to be valid it has to correspond to the response structure of this library.

## Installation

### Via composer (recommended)

Inside your project create a composer.json file (if it does not already exists) and add the following JSON code:
```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/TinfoilHatsInc/TinfoilHMAC"
    }
  ]
}
```

Next, open the terminal or command line, navigate to the project root and execute the following command: 
```bash
composer require tinfoilhatsinc/tinfoil-hmac
```

After installing, you need to require Composer's autoloader:
```php
require 'vendor/autoload.php';
```

### Via plain PHP

First you have to install the following dependencies:
* symfony/yaml (https://github.com/symfony/yaml)
* guzzle/guzzle (https://github.com/guzzle/guzzle)

Next, you have to copy the contents of the TinfoilHMAC folder into one of the include_path directories specified in 
your PHP configuration and load each class file manually.

## Setup

Before the library can be used the `config.example.yml` file must be copied **in the same folder** and renamed to 
`config.yml`.

## Usage

An example of the usage of this library (within clients): 

```php
<?php
// Check if there is a known shared key registered.
if(!TinfoilHMAC\Util\Session::getInstance()->hasKnownSharedKey()) {
  // If no key is registered force the user to login.
  // Then open a new HAPI user session with the user's credentials.
  TinfoilHMAC\Util\UserSession::open('test@test.com', 'test123');
}
// Send a request (shared key generation will be done automatically but needs an active UserSession).
// The request consists of an HTTP method, the CHUB id, the API method and parameters if necessary.
$request = new TinfoilHMAC\API\SecureRequest('GET', 'the-chub-id', 'the-api-method', [
  'param1' => 'value1',
  'param2' => 'value2',
]);
try {
  // The request will automatically be verified with the registered shared key and a TinfoilHMAC\API\SecureResponse
  // object is created.
  $response = $request->send();
  // Check if request was successful
  if(!$response->hasError()) {
    // Do stuff.
  } else {
    // Tell the user the request was unsuccessful and let him try again.
  }
} catch (TinfoilHMAC\Exception\InvalidResponseException $e) {
  // In case the response is invalid a TinfoilHMAC\Exception\InvalidResponseException is thrown.
  // Tell the user the request was unsuccessful and let him try again.
} catch (TinfoilHMAC\Exception\InvalidHMACException $e) {
  // In case the given HMAC is invalid a TinfoilHMAC\Exception\InvalidHMACException is thrown.
  // Tell the user the request was unsuccessful, let him try again and invalidate the existing shared key.
  TinfoilHMAC\Util\Session::getInstance()->invalidateKnownSharedKey();
}
```