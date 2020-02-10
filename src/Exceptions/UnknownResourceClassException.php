<?php

namespace MovaviTest\Exceptions;

use MovaviTest\Exceptions\MovaviTestException;

/**
 * Class UnknownResourceClassException
 *
 * Throws when we can not create the resource class object
 *
 * @package MovaviTest\Exceptions
 */
class UnknownResourceClassException extends MovaviTestException
{
    protected $message = 'Unknown resource class';
}