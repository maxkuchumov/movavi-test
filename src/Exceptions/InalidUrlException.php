<?php


namespace Movavi\Exceptions;


/**
 * Class UrlNotSetException
 *
 * Throws when called getData method of HttpClient object without setting url begore this (calling setUrl method)
 *
 * @package Movavi\Exceptions
 */
class InalidUrlException  extends MovaviException
{
    protected $message = 'Given "url" not valid';
}