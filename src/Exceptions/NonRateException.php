<?php

namespace Movavi\Exceptions;


/**
 * Class NonRateException
 *
 * Throws when has not return any rate data
 *
 * @package Movavi\Exceptions
 */
class NonRateException extends MovaviException
{
    protected $message = 'Resource has not return any rate data';
}