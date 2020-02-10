<?php

namespace MovaviTest\Exceptions;

use MovaviTest\Exceptions\MovaviTestException;

/**
 * Class UnavailableResourceException
 *
 * Throws when the resource is unreachable at the moment
 *
 * @package MovaviTest\Exceptions
 */
class UnavailableResourceException extends MovaviTestException
{
    protected $message = 'Resource is unavailable';
}