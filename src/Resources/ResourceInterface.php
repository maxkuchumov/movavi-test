<?php


namespace MovaviTest\Resources;


interface ResourceInterface {

    public function getRate(string $currencyCode, \DateTime $date): float;

}