<?php

namespace Movavi\Exceptions;

/**
 * Class UnknownResourceClassException
 *
 * Throws when we can not create the resource class object
 *
 * @package Movavi\Exceptions
 */
class UnknownResourceClassException extends MovaviException
{
    protected $message = 'Unknown resource class';
}