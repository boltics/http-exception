<?php

namespace Boltics\HttpException\Tests;

use Boltics\HttpException\Exception;
use Boltics\HttpException\HttpCodeInterface;

class TestException extends Exception
{
    const FIRST_ERROR = [
        'message' => 'Error for testing',
        'errorCode' => 10000,
        'httpCode' => HttpCodeInterface::INTERNAL_SERVER_ERROR,
    ];
}
