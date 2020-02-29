<?php

namespace Movavi\Exceptions;

/**
 * Class UnsupportedCurrencyCodeException
 *
 * Throws when the passed parameter currency code not found in resource currency mapping table
 *
 * @package Movavi\Exception
 */
class UnsupportedCurrencyCodeException extends MovaviException
{
    protected $message = 'Unsupported currency code';
}