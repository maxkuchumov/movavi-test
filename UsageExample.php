<?php

require __DIR__ . '/vendor/autoload.php';

use MovaviTest\Services\CurrencyRatesService;
use MovaviTest\Resources\CbrResource;
use MovaviTest\Resources\RbcResource;
use MovaviTest\Exceptions\MovaviTestException;

$EOL = php_sapi_name() == 'cli' ? PHP_EOL : '<br/>';

try {

    $currencyRatesService = new CurrencyRatesService([CbrResource::NAME, RbcResource::NAME]);

    // get average rate for yesterday from all resources (cbr and rbc)
    $date = new \DateTime('yesterday');
    $avgUsdRate = $currencyRatesService->getAverageRate('USD', $date);
    $avgEurRate = $currencyRatesService->getAverageRate('EUR', $date);
    printf("Yesterday USD average rate : %s%s", $avgUsdRate, $EOL);
    printf("Yesterday Euro average rate : %s%s", $avgEurRate, $EOL);

    // run getAverageRate method for today (without date parameter)
    // (!!! sometimes cbr return nothing for today and it lead to exception !!! )
    $avgUsdRate = $currencyRatesService->getAverageRate('USD');
    $avgEurRate = $currencyRatesService->getAverageRate('EUR');
    printf("Today USD average rate : %s%s", $avgUsdRate, $EOL);
    printf("Today Euro average rate : %s%s", $avgEurRate, $EOL);
    // In this case you can try to get rates only from 'rbc' resource for example:
    /*
        $rbcUsdRate = $currencyRatesService->getRateFromResource('USD', RbcResource::NAME);
        $rbcEurRate = $currencyRatesService->getRateFromResource('EUR', RbcResource::NAME);
        printf("Today USD rate from rbc: %s%s", $rbcUsdRate, $EOL);
        printf("Today EUR rate from rbc: %s%s", $rbcEurRate, $EOL);
     */

    // set the date a year ago:
    $date = new \DateTime('-1 year');
    // get average rate on this date from all available resources (rbc and cbr)
    $avgUsdRate = $currencyRatesService->getAverageRate('USD', $date);
    $avgEurRate = $currencyRatesService->getAverageRate('EUR', $date);

    printf("USD average rate on %s: %s%s", $date->format('d.m.Y'), $avgUsdRate, $EOL);
    printf("Euro average rate on %s: %s%s", $date->format('d.m.Y'), $avgEurRate, $EOL);

} catch (MovaviTestException $e) {

    printf('Error: %s%s', $e->getMessage(), $EOL);
}

