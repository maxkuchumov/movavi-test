<?php

namespace Movavi\Exceptions;

/**
 * Class UnavailableResourceException
 *
 * Throws when the resource is unreachable at the moment
 *
 * @package Movavi\Exceptions
 */
class UnavailableResourceException extends MovaviException
{
    protected $message = 'Resource is unavailable';
}