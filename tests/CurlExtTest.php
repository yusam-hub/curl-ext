<?php

namespace YusamHub\CurlExt\Tests;

use YusamHub\CurlExt\CurlAssert;
use YusamHub\CurlExt\CurlExtDebug;

class CurlExtTest extends \PHPUnit\Framework\TestCase
{
    public function testStatusCode()
    {
        $curlExt = new CurlExtDebug('https://ya.ru', __DIR__ . '/../tmp/CurlExtDebug.log');
        $curlExt->isDebugging = true;
        $curlExt->get('/',['Test' => 'Test'],[]);

        $curlAssert = new CurlAssert($this, $curlExt);
        $curlAssert->assertStatusCode(200);
    }

}