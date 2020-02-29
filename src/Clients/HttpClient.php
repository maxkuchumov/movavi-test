<?php

namespace Movavi\Clients;

use Movavi\Exceptions\UnavailableResourceException;
use Movavi\Exceptions\InalidUrlException;

/**
 * Class HttpClient
 *
 * Very simple implementation of CURL wrapper, just to send request and fetch data from endpoint
 *
 * @package Movavi\Clients
 */
class HttpClient implements ClientInterface
{
    /**
     * Setter for $url filed
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * Timeout setter
     * @param int $timeoutSec
     */
    public function setTimeoutSec(int $timeoutSec): void
    {
        $this->timeoutSec = $timeoutSec;
    }

    /**
     * Returns data from url resource
     *
     * @return string
     * @throws UnavailableResourceException
     */
    public function getData(string $url): string
    {
        if (!$this->validateUrl($url)) {
            throw new InalidUrlException('Givven "url" parameter is not valid');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new UnavailableResourceException('Http Client error: ' . curl_error($ch));
        }

        curl_close($ch);

        return $output;
    }

    protected function validateUrl(string $url): bool
    {
        //TODO: implement validation here

        return true;
    }
}