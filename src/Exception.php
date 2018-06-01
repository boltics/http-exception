<?php

namespace Boltics\HttpException;

use Symfony\Component\HttpFoundation\Response;

class Exception extends \Exception
{
    /**
     * @var integer Http code
     */
    protected $httpCode = null;

    /**
     *
     */
    public function __construct(array $exception)
    {
        $sanitizedException = $this->sanitizeException($exception);

        parent::__construct(
            $sanitizedException['message'],
            $sanitizedException['errorCode']
        );

        if (isset($exception['httpCode']) && $this->isValidHttpCode($exception['httpCode'])) {
            $this->setHttpCode($exception['httpCode']);
        } else {
            $this->setHttpCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Setter for httpCode
     *
     * @param integer $httpCode Http status code
     *
     * @return HttpException\Exception
     */
    public function setHttpCode(int $httpCode)
    {
        $this->httpCode = $httpCode;
        return $this;
    }

    /**
     * Getter for httpCode
     *
     * @param integer $httpCode Http status code
     *
     * @return integer|null
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Initialized error message and code if their is no input or the type is
     *  not \Exception::__construct() supported
     *
     * @param array $exception
     *
     * @return array Array contains necessary information when create an exception
     */
    public static function sanitizeException(array $exception)
    {
        $result = [
            'message' => '',
            'errorCode' => 0,
        ];

        if (isset($exception['message']) && is_string($exception['message'])) {
            $result['message'] = $exception['message'];
        }

        if (isset($exception['errorCode']) && is_int($exception['errorCode'])) {
            $result['errorCode'] = $exception['errorCode'];
        }

        return $result;
    }

    /**
     * Check if http stutus code is in the list
     *
     * @param mixed $httpCode
     *
     * @return boolean
     */
    public static function isValidHttpCode($httpCode)
    {
        if (!is_integer($httpCode)) {
            return false;
        }

        $httpCodeInterface = new \ReflectionClass(Response::class);
        if (!in_array($httpCode, $httpCodeInterface->getConstants())) {
            return false;
        }

        return true;
    }
}
