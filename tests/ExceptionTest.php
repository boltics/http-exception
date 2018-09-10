<?php

namespace Boltics\HttpException\Tests;

use PHPUnit\Framework\TestCase;
use Boltics\HttpException\Exception;
use Symfony\Component\HttpFoundation\Response;
use Boltics\HttpException\Tests\TestException;

class ExceptionTest extends TestCase
{
    /**
     * @var array Default value of Exception info
     */
    private $defaultExceptionInfo = [
        'message' => '',
        'errorCode' => 0,
    ];

    /**
     * Test exception with normal construction
     *
     * @return void
     */
    public function testExceptionConstructor()
    {
        $errorInfo = [
            'message' => 'QQ',
            'errorCode' => 50,
            'httpCode' => 400,
        ];
        $e = new Exception($errorInfo);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getHttpCode());
        $this->assertEquals($errorInfo['message'], $e->getMessage());
        $this->assertEquals($errorInfo['errorCode'], $e->getCode());
        $this->assertEquals($errorInfo['errorCode'], $e->getErrorCode());
    }

    /**
     * Test creating exception without http code
     *
     * @return void
     */
    public function testExceptionConstructingWithoutHttpCode()
    {
        $errorInfo = [
            'message' => 'QQ',
            'errorCode' => 50,
        ];
        $e = new Exception($errorInfo);

        $this->assertNotEquals(Response::HTTP_BAD_REQUEST, $e->getHttpCode());
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getHttpCode());
        $this->assertEquals($errorInfo['message'], $e->getMessage());
        $this->assertEquals($errorInfo['errorCode'], $e->getCode());
        $this->assertEquals($errorInfo['errorCode'], $e->getErrorCode());
    }

    /**
     * Test sanitizeException
     *
     * @return void
     */
    public function testSanitizeException()
    {
        $errorInfo = [
            'message' => 'test',
            'errorCode' => 50,
        ];
        $exception = Exception::sanitizeException($errorInfo);

        $this->assertEquals($errorInfo['message'], $exception['message']);
        $this->assertEquals($errorInfo['errorCode'], $exception['errorCode']);
    }

    /**
     * Test sanitizeException without message
     *
     * @return void
     */
    public function testSanitizeExceptionWithoutMessage()
    {
        $errorInfo = [
            'errorCode' => 50,
        ];
        $exception = Exception::sanitizeException($errorInfo);

        $this->assertEquals($this->defaultExceptionInfo['message'], $exception['message']);
        $this->assertEquals($errorInfo['errorCode'], $exception['errorCode']);
    }

    /**
     * Test sanitizeException without error code
     *
     * @return void
     */
    public function testSanitizeExceptionWithoutErrorCode()
    {
        $errorInfo = [
            'message' => 'testing',
        ];
        $exception = Exception::sanitizeException($errorInfo);

        $this->assertEquals($errorInfo['message'], $exception['message']);
        $this->assertEquals($this->defaultExceptionInfo['errorCode'], $exception['errorCode']);
    }

    /**
     * Test sanitizeException() with array that its elements are incorrect type
     *
     * @return void
     */
    public function testSanitizeExceptionWithIncorrectTypeExceptionInfo()
    {
        $errorInfo = [
            'message' => [
                'test' => true,
                'number' => 123,
            ],
            'errorCode' => 'error code'
        ];
        $exception = Exception::sanitizeException($errorInfo);
        $this->assertEquals($this->defaultExceptionInfo['message'], $exception['message']);
        $this->assertEquals($this->defaultExceptionInfo['errorCode'], $exception['errorCode']);
    }

    /**
     * @return void
     */
    public function testIsValidHttpCode()
    {
        $this->assertTrue(Exception::isValidHttpCode(Response::HTTP_BAD_REQUEST));
    }

    /**
     * @return void
     */
    public function testIsValidHttpCodeWithString()
    {
        $this->assertFalse(Exception::isValidHttpCode('testing'));
    }

    /**
     * @return void
     */
    public function testIsValidHttpCodeWithCodeNotInList()
    {
        $this->assertFalse(Exception::isValidHttpCode(600));
    }

    /**
     * @return void
     */
    public function testInheritanceOfException()
    {
        $e = new TestException(TestException::FIRST_ERROR);
        $this->assertEquals(TestException::FIRST_ERROR['httpCode'], $e->getHttpCode());
        $this->assertEquals(TestException::FIRST_ERROR['message'], $e->getMessage());
        $this->assertEquals(TestException::FIRST_ERROR['errorCode'], $e->getCode());
        $this->assertEquals(TestException::FIRST_ERROR['errorCode'], $e->getErrorCode());
    }

    /**
     * @return void
     */
    public function testGettingDefaultAdditionalData()
    {
        $e = new TestException(TestException::FIRST_ERROR);

        $this->assertEquals([], $e->getAdditionalData());
    }

    /**
     * @return void
     */
    public function testGetterAndSetterOfAdditionalData()
    {
        $testingData = [__FUNCTION__];

        $e = new TestException(TestException::FIRST_ERROR);
        $e->setAdditionalData($testingData);

        $this->assertEquals($testingData, $e->getAdditionalData());
    }

    /**
     * @return void
     */
    public function testAppendAdditionalData()
    {
        $testingData = [
            'a' => __FUNCTION__
        ];

        $e = new TestException(TestException::FIRST_ERROR);
        $e->setAdditionalData($testingData);
        $this->assertEquals($testingData, $e->getAdditionalData());

        $testingData['b'] = __CLASS__;
        $e->appendAdditionalData(__CLASS__, 'b');
        $this->assertEquals($testingData, $e->getAdditionalData());
    }

    /**
     * Test append additional data without key
     *
     * @return void
     */
    public function testAppendAdditionalDataWithoutKey()
    {
        $testingData = [__FUNCTION__];

        $e = new TestException(TestException::FIRST_ERROR);
        $e->setAdditionalData($testingData);
        $this->assertEquals($testingData, $e->getAdditionalData());

        $testingData[] = __CLASS__;
        $e->appendAdditionalData(__CLASS__);
        $this->assertEquals($testingData, $e->getAdditionalData());

        $testingData[] = __NAMESPACE__;
        $e->appendAdditionalData(__NAMESPACE__);
        $this->assertEquals($testingData, $e->getAdditionalData());
    }
}
