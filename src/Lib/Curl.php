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
     * URL string for fetching data from
     *
     * @var string
     */
    protected $url;

    /**
     * Timeout - the maximum number of seconds to allow cURL functions to execute.
     *
     * @var integer
     */
    protected $timeoutSec;
    /**
     * Curl constructor.
     * @param string $url
     */
    public function __construct(string $url, int $timeoutSec = 10)
    {
        // TODO: validate url

        $this->url = $url;
        $this->timeoutSec = $timeoutSec;
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
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeoutSec);
        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new UnavailableResourceException('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        return $output;
    }
}