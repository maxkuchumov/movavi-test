<?php


namespace MovaviTest\Clients;

use Movavi\Clients\ClientInterface;


class HttpClientBadDataMock implements ClientInterface
{
    public function getData(string $url): string
    {
        return '<html>bad data!</html>>';
    }

}