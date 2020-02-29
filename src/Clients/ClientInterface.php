<?php

namespace Movavi\Clients;

interface ClientInterface
{
    public function getData(string $url): string;
}