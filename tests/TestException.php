<?php

namespace MajaLin\HttpException\Tests;

use MajaLin\HttpException\Exception;
use MajaLin\HttpException\HttpCodeInterface;

class TestException extends Exception
{
    const FIRST_ERROR = [
        'message' => 'Error for testing',
        'errorCode' => 10000,
        'httpCode' => HttpCodeInterface::INTERNAL_SERVER_ERROR,
    ];
}
