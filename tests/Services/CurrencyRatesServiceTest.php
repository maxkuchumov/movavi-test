<?php


namespace MovaviTest\Tests;

use PHPUnit\Framework\TestCase;
use MovaviTest\Services\CurrencyRatesService;
use MovaviTest\Resources\CbrResource;
use MovaviTest\Resources\RbcResource;
use MovaviTest\Resources\ResourceInterface;
use MovaviTest\Exceptions\UnsupportedCurrencyCodeException;
use MovaviTest\Exceptions\UnknownResourceClassException;

class CurrencyRatesServiceTest extends TestCase
{
    public function test_getAverageRate_returnPositiveFloatRate()
    {
        $currencyRatesService = new CurrencyRatesService();

        $date = new \DateTime('08.02.2020');
        $testRate = $currencyRatesService->getAverageRate('USD', $date);
        $this->assertInternalType('float', $testRate);
        $this->assertGreaterThan(0, $testRate);
        $testRate = $currencyRatesService->getAverageRate('EUR', $date);
        $this->assertInternalType('float', $testRate);
        $this->assertGreaterThan(0, $testRate);

    }

    /**
     * @expectedException MovaviTest\Exceptions\UnsupportedCurrencyCodeException
     */
    public function test_getAverageRate_ThrowUnsupportedCurrencyCodeException()
    {
        $currencyRatesService = new CurrencyRatesService();

        $date = new \DateTime('08.02.2020');
        $currencyRatesService->getAverageRate('UNKNOWN', $date);
    }

    /**
     * @expectedException MovaviTest\Exceptions\UnsupportedCurrencyCodeException
     */
    public function test_getRateFromResource_ThrowUnsupportedCurrencyCodeException()
    {
        $currencyRatesService = new CurrencyRatesService();

        $date = new \DateTime('08.02.2020');
        $currencyRatesService->getRateFromResource('UNKNOWN', RbcResource::class, $date);
    }

    /**
     * @expectedException MovaviTest\Exceptions\UnknownResourceClassException
     */
    public function test_createCurrencyRatesService_ThrowUnknownResourceClassException()
    {
        $currencyRatesService = new CurrencyRatesService(['UNKNOWN']);
    }

    /**
     * @expectedException MovaviTest\Exceptions\UnknownResourceClassException
     */
    public function test_getRateFromResource_ThrowUnknownResourceClassException()
    {
        $currencyRatesService = new CurrencyRatesService();

        $date = new \DateTime('08.02.2020');
        $currencyRatesService->getRateFromResource('USD', 'UNKNOWN', $date);
    }

    public function test_createCurrencyRatesService_ResourcesContainsTwoObjects()
    {
        $currencyRatesService = new CurrencyRatesService([CbrResource::class, RbcResource::class]);
        $refResources = new \ReflectionProperty(get_class($currencyRatesService), 'resources');
        $refResources->setAccessible(true);
        $resourcesArr = $refResources->getValue($currencyRatesService);
        $this->assertCount(2, $resourcesArr);
        foreach ($resourcesArr as $resourceObj) {

            $this->assertTrue($resourceObj instanceof ResourceInterface);
        }
    }
}
