<?php

namespace MovaviTest\Exceptions;

use MovaviTest\Exceptions\MovaviTestException;

/**
 * Class NonRateException
 *
 * Throws when has not return any rate data
 *
 * @package MovaviTest\Exceptions
 */
class NonRateException extends MovaviTestException
{
    protected $message = 'Resource has not return any rate data';
}