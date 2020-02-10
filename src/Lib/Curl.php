<?php

namespace MovaviTest\Lib;

use MovaviTest\Exceptions\UnavailableResourceException;

/**
 * Class Curl
 *
 * Very simple implementation of CURL wrapper, just to send request and fetch data from endpoint
 *
 * @package MovaviTest\Lib
 */
class Curl
{
    /**
     *  default timeout in seconds
     */
    const CURL_TIMEOUT = 10;

    /**
     * URL string for fetching data from
     *
     * @var string
     */
    protected $url;


    /**
     * Curl constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        // TODO: validate url

        $this->url = $url;
    }

    /**
     * Returns data from url resource
     *
     * @return string
     * @throws UnavailableResourceException
     */
    public function getData(): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);
        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new UnavailableResourceException('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        return $output;
    }
}