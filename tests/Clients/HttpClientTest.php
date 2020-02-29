<?php

namespace MovaviTest\Clients;

use PHPUnit\Framework\TestCase;
use Movavi\Clients\HttpClient;

class HttpClientTest extends TestCase
{
    /**
     * @expectedException Movavi\Exceptions\UnavailableResourceException
     */
    public function test_GetData_ThrowUnavailableResourceException()
    {
        $httpClient = new HttpClient();
        $httpClient->getData('bad_url');
    }
}
