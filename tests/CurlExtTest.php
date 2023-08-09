<?php

namespace YusamHub\CurlExt\Tests;

use YusamHub\CurlExt\CurlAssert;
use YusamHub\CurlExt\CurlExtDebug;

class CurlExtTest extends \PHPUnit\Framework\TestCase
{
    /*public function testDefault()
    {
        date_default_timezone_set('Europe/Moscow');
        $time = time();
        var_dump(date("Y-m-d H:i:s", $time));
        var_dump(date("Y-m-d H:i:s", curl_ext_time_utc($time)));
    }*/

   /* public function testStatusCode()
    {
        $curlExt = new CurlExtDebug('https://ya.ru', __DIR__ . '/../tmp/CurlExtDebug.log');
        $curlExt->isDebugging = true;
        $curlExt->get('/',['Test' => 'Test'],[]);

        $curlAssert = new CurlAssert($this, $curlExt);
        $curlAssert->assertStatusCode(200);
    }*/

}