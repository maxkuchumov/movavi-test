<?php


namespace Movavi\Exceptions;

/**
 * Class EmptyResourceListException
 *
 * Throws when $resourceClasses parameter is empty (in CurrencyRatesService -> getAverageRateFromResources method)
 *
 * @package Movavi\Exceptions
 */
class EmptyResourceListException extends MovaviException
{
    protected $message = 'Empty resource classes list parameter';
}