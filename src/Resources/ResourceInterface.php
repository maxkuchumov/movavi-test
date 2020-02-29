<?php


namespace Movavi\Resources;

use Movavi\Clients\ClientInterface;

interface ResourceInterface {

    public function __construct(ClientInterface $client);

    public function getRate(string $currencyCode, \DateTime $date): float;

}