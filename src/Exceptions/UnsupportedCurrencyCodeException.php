<?php

namespace MovaviTest\Exceptions;

use MovaviTest\Exceptions\MovaviTestException;

/**
 * Class UnsupportedCurrencyCodeException
 *
 * Throws when the passed parameter currency code not found in resource currency mapping table
 *
 * @package MovaviTest\Exception
 */
class UnsupportedCurrencyCodeException extends MovaviTestException
{
    protected $message = 'Unsupported currency code';
}