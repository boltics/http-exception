# PHP Exception with http status code

A exception that extends PHP exception and also contains http status code.

Now you can easily
 manage your error code and http status code together!

## Installation

```
composer require majalin\http-exception
```
## Declaration

```php
use HttpException\Exception;
use HttpException\HttpCodeInterface;
```


## Usage

```php
$errorInfo = [
    'message' => 'Hola',
    'errorCode' => 1234,
    'httpCode' => HttpCodeInterface::BAD_REQUEST
];

throw new Exception($errorInfo);

// or

class CustomizedException extends Exception
{
    const FIRST_ERROR = [
        'message' => 'Hola',
        'errorCode' => 1234,
        'httpCode' => HttpCodeInterface::BAD_REQUEST
    ];
}

throw new CustomizedException(CustomizedException::FIRST_ERROR);
```

## Documentation

- `__construct()` The constructor checks that the value exist in the enum
- `getHttpCode()` Returns http code
- `setHttpCode()` Set http code

Static methods:

- `isValidHttpCode()` method Returns boolean that http code is valid (in the supported list)
- `sanitizeException()` method Returns valid exception info for __construct()
