<?php

namespace Boltics\HttpException\Tests;

use Boltics\HttpException\Exception;
use Symfony\Component\HttpFoundation\Response;

class TestException extends Exception
{
    const FIRST_ERROR = [
        'message' => 'Error for testing',
        'errorCode' => 10000,
        'httpCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
    ];
}
