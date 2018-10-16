<?php

namespace MajaLin\HttpException;

use Symfony\Component\HttpFoundation\Response;

class Exception extends \Exception
{
    /**
     * @var integer Http code
     */
    protected $httpCode = null;

    /**
     * @var array Additional data you want to put
     */
    protected $additionalData = [];

    /**
     * Constructor
     *
     * @param array $exception Required array
     * @param array $additionalData = []
     */
    public function __construct(array $exception, array $additionalData = [])
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

        if (!empty($additionalData)) {
            $this->setAdditionalData($additionalData);
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
     * An alternative way to get exception error code
     *
     * @return integer
     */
    public function getErrorCode(): int
    {
        return $this->getCode();
    }

    /**
     * Set additionalData in exception
     *
     * @param array $additionalData
     *
     * @return HttpException\Exception
     */
    public function setAdditionalData(array $additionalData)
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    /**
     * Get additional data
     *
     * @return array
     */
    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }

    /**
     * Append data into the additional data with key
     *
     * @param mixed $additionalData
     * @param mixed $key = null
     *
     * @return HttpException\Exception
     */
    public function appendAdditionalData($additionalData, $key = null)
    {
        if (isset($key)) {
            $this->additionalData[$key] = $additionalData;
        } else {
            $this->additionalData[] = $additionalData;
        }

        return $this;
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
