<?php


namespace MovaviTest\Exceptions;

use MovaviTest\Exceptions\MovaviTestException;

/**
 * Class EmptyResourceListException
 *
 * Throws when $resourceClasses parameter is empty (in CurrencyRatesService -> getAverageRateFromResources method)
 *
 * @package MovaviTest\Exceptions
 */
class EmptyResourceListException extends MovaviTestException
{
    protected $message = 'Empty resource classes list parameter';
}