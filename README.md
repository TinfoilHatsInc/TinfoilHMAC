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
incoming requests from clients using this library and sending responses.
The SecureRequest class can be used to send secure requests to the registered API and will, if a *valid response is 
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
`config.yml`. The missing value(s) have to be defined and other values can be changed according to your configuration.