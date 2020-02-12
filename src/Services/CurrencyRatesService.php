<?php

namespace MovaviTest\Services;

use MovaviTest\Resources\RbcResource;
use MovaviTest\Resources\CbrResource;
use MovaviTest\Resources\ResourceInterface;
use MovaviTest\Exceptions\UnknownResourceClassException;
use MovaviTest\Exceptions\EmptyResourceListException;


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
     * The total list of supported resource classes (by default)
     */
    const AVAILABLE_RESOURCE_CLASSES = [RbcResource::class, CbrResource::class];

    /**
     * resource objects array
     * @var array
     */
    protected $resources = [];

    /**
     * CurrencyRatesService constructor.
     * @param array $resourceClasses
     * @throws UnknownResourceClassException
     */
    public function __construct(array $resourceClasses = [])
    {
        if (empty($resourceClasses)) {
            // by default all available resources will be used
            $resourceClasses = self::AVAILABLE_RESOURCE_CLASSES;
        }

        $resourceClasses = array_unique($resourceClasses);

        // create resource objects from resources list
        foreach ($resourceClasses as $resourceClass) {

            if (class_exists($resourceClass, true) && in_array(ResourceInterface::class, class_implements($resourceClass))) {

                $this->resources[$resourceClass] = new $resourceClass();
            } else {

                throw new UnknownResourceClassException('Unknown resource class: ' . $resourceClass);
            }
        }
    }

    /**
     * Sends request to particular resource
     *
     * @param string $resourceClass
     * @param string $currencyCode
     * @param \DateTime|null $date
     * @return float
     * @throws UnknownResourceClassException
     */
    public function getRateFromResource(string $resourceClass, string $currencyCode, \DateTime $date = null): float
    {
        if (!in_array($resourceClass, array_keys($this->resources))) {
            throw new UnknownResourceClassException('Unknown resource class: ' . $resourceClass);
        }

        if (is_null($date)) {
            $date = new \DateTime(); // today by default
        }

        return $this->resources[$resourceClass]->getRate($currencyCode, $date);
    }

    /**
     * Sends requests to all available resources to get rate and calculate the average value
     *
     * @param string $currencyCode
     * @param \DateTime|null $date
     * @return float
     * @throws UnknownResourceClassException
     */
    public function getAverageRate(string $currencyCode, \DateTime $date = null): float
    {
        // run for all available resources
        return $this->getAverageRateFromResources(array_keys($this->resources), $currencyCode, $date);
    }


    /**
     * Send request to resources from the list to get rate and calculate the average value
     *
     * @param array $resourceClasses
     * @param string $currencyCode
     * @param \DateTime|null $date
     * @return float
     * @throws EmptyResourceListException
     * @throws UnknownResourceClassException
     */
    public function getAverageRateFromResources(array $resourceClasses, string $currencyCode, \DateTime $date = null): float
    {
        if (empty($resourceClasses)) {
            throw new EmptyResourceListException();
        }

        $unknownResources = array_diff($resourceClasses, array_keys($this->resources));
        if (!empty($unknownResources)) {
            throw new UnknownResourceClassException('Unknown resource class(es): ' . implode(', ', $unknownResources));
        }

        if (is_null($date)) {
            $date = new \DateTime(); // today by default
        }

        $rates = [];
        foreach ($resourceClasses as $resourceClass) {
            $rates[] = $this->resources[$resourceClass]->getRate($currencyCode, $date);
        }

        return array_sum($rates) / count($rates);
    }

}