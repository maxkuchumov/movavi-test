<?php

namespace MovaviTest\Tests;

use PHPUnit\Framework\TestCase;
use MovaviTest\Lib\Curl;
use MovaviTest\Exceptions\UnavailableResourceException;

class CurlTest extends TestCase
{
    /**
     * @expectedException MovaviTest\Exceptions\UnavailableResourceException
     */
    public function test_GetData_ThrowUnavailableResourceException()
    {
        $curlObj = new Curl('bad_url');
        $curlObj->getData();
    }
}
