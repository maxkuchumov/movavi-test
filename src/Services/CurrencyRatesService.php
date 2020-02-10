<?php

namespace MovaviTest\Services;

use MovaviTest\Resources\RbcResource;
use MovaviTest\Resources\CbrResource;
use MovaviTest\Exceptions\UnknownResourceClassException;


/**
 * Class CurrencyRatesService
 *
 * Manages available resources, routes requests to resources, receives data and aggregates
 *
 * @package MovaviTest\Services
 */
class CurrencyRatesService
{
    /**
     * The total list of supported resources
     */
    const AVAILABLE_RESOURCES = [RbcResource::NAME, CbrResource::NAME];

    /**
     * resource objects array
     * @var array
     */
    protected $resources = [];

    /**
     * CurrencyRatesService constructor.
     * @param array $resources
     * @throws UnknownResourceClassException
     */
    public function __construct(array $resources = [])
    {

        if (empty($resources)) {
            // by default all available resources will be used
            $resources = self::AVAILABLE_RESOURCES;
        }

        $resources = array_unique($resources);

        // create resource objects from resources list
        foreach ($resources as $resourceCode) {
            $resourceClass = 'MovaviTest\\Resources\\' . ucfirst($resourceCode) . 'Resource';
            if (in_array($resourceClass, get_declared_classes())) {

                $this->resources[$resourceCode] = new $resourceClass();
            } else {

                throw new UnknownResourceClassException('Unknown resource class: ' . $resourceClass);
            }
        }
    }

    /**
     * Sends request to particular resource
     *
     * @param string $currencyCode
     * @param string $resourceCode
     * @param \DateTime|null $date
     * @return float
     * @throws UnknownResourceClassException
     */
    public function getRateFromResource(string $currencyCode, string $resourceCode, \DateTime $date = null): float
    {
        $resourceCode = strtolower($resourceCode);
        if (!in_array($resourceCode, array_keys($this->resources))) {
            throw new UnknownResourceClassException('Unknown resource code: ' . $resourceCode);
        }

        if (is_null($date)) {
            $date = new \DateTime(); // today by default
        }

        return $this->resources[$resourceCode]->getRate($currencyCode, $date);
    }

    /**
     * Sends requests to all available resources to get rate and calculate average value
     *
     * @param string $currencyCode
     * @param \DateTime|null $date
     * @return float
     * @throws UnknownResourceClassException
     */
    public function getAverageRate(string $currencyCode, \DateTime $date = null): float
    {
        $rates = [];
        foreach (eys($this->resources) as $resourceCode) {
            $rates[] = $this->getRateFromResource($currencyCode, $resourceCode, $date);
        }

        return array_sum($rates) / count($rates);
    }

}