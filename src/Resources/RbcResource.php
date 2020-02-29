<?php

namespace Movavi\Resources;

use Movavi\Clients\ClientInterface;
use Movavi\Exceptions\UnsupportedCurrencyCodeException;
use Movavi\Exceptions\NonRateException;

/**
 * Class RbcResource
 *
 * Provides fetching currency rates from cash.rbc.ru
 * @package Movavi\Resources
 */
class RbcResource implements ResourceInterface
{
    /**
     *  Resource url without parameters
     */
    const URL = 'https://cash.rbc.ru/cash/json/converter_currency_rate/';

    /**
     *  Date format for URL date parameters
     */
    const URL_DATE_FORMAT = 'Y-m-d';

    /**
     *  Mapping table for matching currency codes in the program and currency codes in the URL string
     */
    const CURRENCY_CODES_MAPPING = [
        'USD' => 'USD',
        'EUR' => 'EUR',
        'RUB' => 'RUR',
    ];


    /**
     * Object for retrieving data from resource
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * RbcResource constructor.
     */
    public function __construct(ClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Fetch rate from rbc resource
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
        $jsonData = $this->httpClient->getData($this->buildUrl($currencyCode, $date));

        return $this->parseJsonResponse($jsonData);
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

        $urlCurrencyCode = static::CURRENCY_CODES_MAPPING[strtoupper($currencyCode)] ?? '';
        if (empty($urlCurrencyCode)) {
            throw new UnsupportedCurrencyCodeException('Unsupported currency code: ' . $currencyCode);
        }

        $parameters = [
            'currency_from' => $urlCurrencyCode,
            'currency_to' => static::CURRENCY_CODES_MAPPING['RUB'],
            'source' => 'cbrf',
            'sum' => 1,
            'date' => $date->format(static::URL_DATE_FORMAT)
        ];

        return static::URL . '?' . http_build_query($parameters);
    }

    /**
     * Gets json data and pulls the currency rate from it
     *
     * @param $jsonData
     * @return float
     * @throws NonRateException
     */
    protected function parseJsonResponse($jsonData): float
    {
        $dataObj = json_decode($jsonData);

        if (empty($dataObj->data->sum_result)) {
            throw new NonRateException('Resource cach.rbc.ru has not return any rate data');
        }

        return floatval($dataObj->data->sum_result);
    }
}