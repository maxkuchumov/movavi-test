<?php

require_once __DIR__ . '/vendor/autoload.php';

use MovaviTest\Services\CurrencyRatesService;
use MovaviTest\Resources\CbrResource;
use MovaviTest\Resources\RbcResource;
use MovaviTest\Exceptions\MovaviTestException;

$EOL = php_sapi_name() == 'cli' ? PHP_EOL : '<br/>';

try {

    $currencyRatesService = new CurrencyRatesService([CbrResource::class, RbcResource::class]);

} catch (MovaviTestException $e) {

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

} catch (MovaviTestException $e) {

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
        $rbcUsdRate = $currencyRatesService->getRateFromResource('USD', RbcResource::class);
        $rbcEurRate = $currencyRatesService->getRateFromResource('EUR', RbcResource::class);
        printf("Today USD rate from rbc: %s%s", $rbcUsdRate, $EOL);
        printf("Today EUR rate from rbc: %s%s%s", $rbcEurRate, $EOL, $EOL);
} catch (MovaviTestException $e) {

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

} catch (MovaviTestException $e) {

    printf('Error: %s%s%s', $e->getMessage(), $EOL, $EOL);
}



