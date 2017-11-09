<?php

namespace HttpException\Tests;

use HttpException\Exception;
use HttpException\HttpCodeInterface;

class TestException extends Exception
{
    const FIRST_ERROR = [
        'message' => 'Error for testing',
        'errorCode' => 10000,
        'httpCode' => HttpCodeInterface::INTERNAL_SERVER_ERROR,
    ];
}
