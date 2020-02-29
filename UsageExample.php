<?php

require_once __DIR__ . '/vendor/autoload.php';

use Movavi\Services\CurrencyRatesService;
use Movavi\Resources\CbrResource;
use Movavi\Resources\RbcResource;
use Movavi\Clients\HttpClient;
use Movavi\Exceptions\MovaviException;


$EOL = php_sapi_name() == 'cli' ? PHP_EOL : '<br/>';

try {

    $currencyRatesService = new CurrencyRatesService(new HttpClient, [CbrResource::class, RbcResource::class]);

} catch (MovaviException $e) {

    printf('Cannot create service object: %s%s%s', $e->getMessage(), $EOL, $EOL);
    die();
}


try {
    // get average rate for yesterday from all resources (cbr and rbc)
    $date = new \DateTime('yesterday');
    $avgUsdRate = $currencyRatesService->getAverageRate('USD', $date);
    $avgEurRate = $currencyRatesService->getAverageRate('EUR', $date);
    printf("Yesterday USD average rate : %s%s", $avgUsdRate, $EOL);
    printf("Yesterday Euro average rate : %s%s%s", $avgEurRate, $EOL, $EOL);

} catch (MovaviException $e) {

    printf('Error: %s%s%s', $e->getMessage(), $EOL, $EOL);
}

try {
    // run getAverageRate method for today (without date parameter)
    // (!!! sometimes cbr return nothing for some day and it lead to exception !!! )
    //
    $avgUsdRate = $currencyRatesService->getAverageRate('USD');
    $avgEurRate = $currencyRatesService->getAverageRate('EUR');
    printf("Today USD average rate : %s%s", $avgUsdRate, $EOL);
    printf("Today Euro average rate : %s%s%s", $avgEurRate, $EOL, $EOL);
    // In this case you can try to get rates only from 'rbc' resource for example:
    //    $rbcUsdRate = $currencyRatesService->getRateFromResource(RbcResource::class, 'USD');
    //    $rbcEurRate = $currencyRatesService->getRateFromResource(RbcResource::class, 'EUR');
} catch (MovaviException $e) {

    printf('Error: %s%s%s', $e->getMessage(), $EOL, $EOL);
}


try {
    // set the date a year ago:
    $date = new \DateTime('-2 year');
    // get average rate on this date from all available resources (rbc and cbr)
    $avgUsdRate = $currencyRatesService->getAverageRate('USD', $date);
    $avgEurRate = $currencyRatesService->getAverageRate('EUR', $date);
    printf("USD average rate for %s: %s%s", $date->format('d.m.Y'), $avgUsdRate, $EOL);
    printf("Euro average rate for %s: %s%s%s", $date->format('d.m.Y'), $avgEurRate, $EOL, $EOL);

} catch (MovaviException $e) {

    printf('Error: %s%s%s', $e->getMessage(), $EOL, $EOL);
}



