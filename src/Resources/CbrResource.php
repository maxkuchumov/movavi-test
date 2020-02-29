<?php

namespace Movavi\Resources;

use Movavi\Clients\ClientInterface;
use Movavi\Exceptions\UnsupportedCurrencyCodeException;
use Movavi\Exceptions\NonRateException;

/**
 * Class CbrResource
 *
 * Provides fetching currency rates from cbr.ru
 * @package Movavi\Resources
 */
class CbrResource implements ResourceInterface
{
    /**
     *  Resource url without parameters
     */
    const URL = 'http://www.cbr.ru/scripts/XML_dynamic.asp';

    /**
     *  Date format for URL date parameters
     */
    const URL_DATE_FORMAT = 'd/m/Y';

    /**
     *  Mapping table for matching currency codes in the program and currency codes in the URL string
     */
    const CURRENCY_CODES_MAPPING = [
        'USD' => 'R01235',
        'EUR' => 'R01239'
    ];


    /**
     * Object for retrieving data from resource
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * CbrResource constructor.
     */
    public function __construct(ClientInterface $client)
    {
        $this->httpClient = $client;
    }


    /**
     * Fetch rate from cbr resource
     *
     * @param string $currencyCode
     * @param \DateTime $date
     * @return float
     * @throws NonRateException
     * @throws UnsupportedCurrencyCodeException
     * @throws \Movavi\Exceptions\UnavailableResourceException
     */
    public function getRate(string $currencyCode, \DateTime $date): float
    {
        $xmlData = $this->httpClient->getData($this->buildUrl($currencyCode, $date));

        return $this->parseXmlResponse($xmlData);
    }

    /**
     * Builds final URL string with parameters
     *
     * @param string $currencyCode
     * @param \DateTime $date
     * @return string
     * @throws UnsupportedCurrencyCodeException
     */
    protected function buildUrl(string $currencyCode, \DateTime $date): string
    {
        $urlDate = $date->format(static::URL_DATE_FORMAT);
        $urlCurrencyCode = static::CURRENCY_CODES_MAPPING[$currencyCode] ?? '';
        if (empty($urlCurrencyCode)) {
            throw new UnsupportedCurrencyCodeException('Unsupported currency code: ' . $currencyCode);
        }

        $parameters = [
            'date_req1' => $urlDate,
            'date_req2' => $urlDate,
            'VAL_NM_RQ' => $urlCurrencyCode
        ];

        return static::URL . '?' . http_build_query($parameters);
    }

    /**
     * Parse xml data and pulls the currency rate from it
     *
     * @param $xmlData
     * @return float
     * @throws NonRateException
     */
    protected function parseXmlResponse(string $xmlData): float
    {
        $xml = @simplexml_load_string($xmlData);
        $parsedData = json_decode(json_encode($xml), true);

        if (!$parsedData || empty($parsedData['Record']) || !isset($parsedData['Record']['Value'])) {
            throw new NonRateException('Resource cbr.ru has not return any rate data');
        }

        $rate = $parsedData['Record']['Value'];
        return floatval(str_replace(',', '.', $rate));
    }

}