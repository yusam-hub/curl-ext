<?php

namespace YusamHub\CurlExt;

/**
 * Class CurlAssert
 * @package YusamHub\CurlExt
 */
class CurlAssert
{
    /**
     * @var \PHPUnit\Framework\TestCase
     */
    protected \PHPUnit\Framework\TestCase $testCase;

    /**
     * @var CurlExt
     */
    protected CurlExt $curlExt;

    /**
     * CurlAssert constructor.
     * @param \PHPUnit\Framework\TestCase $testCase
     * @param CurlExt $curlExt
     */
    public function __construct(\PHPUnit\Framework\TestCase $testCase, CurlExt $curlExt)
    {
        $this->testCase = $testCase;
        $this->curlExt = $curlExt;
    }

    /**
     * @param int $value
     * @return void
     */
    public function assertStatusCode(int $value)
    {
        $this->testCase->assertTrue(
            $this->curlExt->getLastResponse()->isStatusCode($value),
            sprintf('Expect status code: %d, returned: %d, request: %s %s%s',
                $value,
                $this->curlExt->getLastResponse()->getStatusCode(),
                $this->curlExt->getLastRequest()->getRequestMethod(),
                $this->curlExt->getLastRequest()->getBaseUrl(),
                $this->curlExt->getLastRequest()->getRequestUri()
            )
        );
    }
}