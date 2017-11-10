# PHP Exception with http status code
[![Build Status](https://travis-ci.org/Boltics/http-exception.svg?branch=master)](https://travis-ci.org/Boltics/http-exception)
[![Coverage Status](https://coveralls.io/repos/github/Boltics/http-exception/badge.svg?branch=master)](https://coveralls.io/github/Boltics/http-exception?branch=master)


A exception that extends PHP exception and also contains http status code.

Now you can easily
 manage your error code and http status code together!

## Installation

```
composer require boltics\http-exception
```
## Declaration

```php
use Boltics\HttpException\Exception;
use Boltics\HttpException\HttpCodeInterface;
```


## Usage

```php
$errorInfo = [
    'message' => 'Hola',
    'errorCode' => 1234,
    'httpCode' => HttpCodeInterface::BAD_REQUEST
];

throw new Exception($errorInfo);

// For Laravel Response
// You can use one exception to manage both error code and http code

class CustomizedException extends Exception
{
    const FIRST_ERROR = [
        'message' => 'Hola',
        'errorCode' => 1234,
        'httpCode' => HttpCodeInterface::BAD_REQUEST
    ];
}

try {
    // do something
    throw new CustomizedException(CustomizedException::FIRST_ERROR);
} catch (CustomizedException $e) {
    return $reponse()->json($data, $e->getHttpCode())
}


```

## Documentation

- `__construct()` The constructor checks that the value exist in the enum
- `getHttpCode()` Returns http code
- `setHttpCode()` Set http code

Static methods:

- `isValidHttpCode()` method Returns boolean that http code is valid (in the supported list)
- `sanitizeException()` method Returns valid exception info for __construct()
