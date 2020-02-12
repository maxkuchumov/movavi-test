<?php

namespace MovaviTest\Tests;

use PHPUnit\Framework\TestCase;
use MovaviTest\Lib\HttpClient;

class HttpClientTest extends TestCase
{
    /**
     * @expectedException MovaviTest\Exceptions\UnavailableResourceException
     */
    public function test_GetData_ThrowUnavailableResourceException()
    {
        $httpClient = new HttpClient('bad_url');
        $httpClient->getData();
    }
}
