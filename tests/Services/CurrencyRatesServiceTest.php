<?php


namespace MovaviTest\Services;

use PHPUnit\Framework\TestCase;
use Movavi\Services\CurrencyRatesService;
use Movavi\Resources\CbrResource;
use Movavi\Resources\RbcResource;
use Movavi\Resources\ResourceInterface;
use MovaviTest\Clients\HttpClientMock;

class CurrencyRatesServiceTest extends TestCase
{

    /**
     * @expectedException Movavi\Exceptions\UnsupportedCurrencyCodeException
     */
    public function test_getAverageRate_ThrowUnsupportedCurrencyCodeException()
    {
        $currencyRatesService = new CurrencyRatesService(new HttpClientMock());

        $date = new \DateTime();
        $currencyRatesService->getAverageRate('UNKNOWN', $date);
    }

    /**
     * @expectedException Movavi\Exceptions\UnsupportedCurrencyCodeException
     */
    public function test_getRateFromResource_ThrowUnsupportedCurrencyCodeException()
    {
        $currencyRatesService = new CurrencyRatesService(new HttpClientMock());

        $date = new \DateTime();
        $currencyRatesService->getRateFromResource(RbcResource::class, 'UNKNOWN', $date);
    }

    /**
     * @expectedException Movavi\Exceptions\UnknownResourceClassException
     */
    public function test_createCurrencyRatesService_ThrowUnknownResourceClassException()
    {
        $currencyRatesService = new CurrencyRatesService(new HttpClientMock(), ['UNKNOWN']);
    }

    /**
     * @expectedException Movavi\Exceptions\UnknownResourceClassException
     */
    public function test_getRateFromResource_ThrowUnknownResourceClassException()
    {
        $currencyRatesService = new CurrencyRatesService(new HttpClientMock());

        $date = new \DateTime();
        $currencyRatesService->getRateFromResource('UNKNOWN', 'USD', $date);
    }

    public function test_createCurrencyRatesService_ResourcesContainsTwoObjects()
    {
        $currencyRatesService = new CurrencyRatesService(new HttpClientMock(), [CbrResource::class, RbcResource::class]);
        $refResources = new \ReflectionProperty(get_class($currencyRatesService), 'resources');
        $refResources->setAccessible(true);
        $resourcesArr = $refResources->getValue($currencyRatesService);
        $this->assertCount(2, $resourcesArr);
        foreach ($resourcesArr as $resourceObj) {

            $this->assertTrue($resourceObj instanceof ResourceInterface);
        }
    }

    /**
     * @expectedException Movavi\Exceptions\EmptyResourceListException
     */
    public function test_getAverageRateFromResource_TrowEmptyResourceListException()
    {
        $currencyRatesService = new CurrencyRatesService(new HttpClientMock(), [CbrResource::class, RbcResource::class]);
        $currencyRatesService->getAverageRateFromResources([], 'USD');
    }

    /**
     * @expectedException Movavi\Exceptions\UnknownResourceClassException
     */
    public function test_getAverageRateFromResource_TrowUnknownResourceClassException()
    {
        $currencyRatesService = new CurrencyRatesService(new HttpClientMock());
        $currencyRatesService->getAverageRateFromResources([CbrResource::class, 'UNKNOWN_CLASS'], 'USD');
    }

    public function test_getAverageRateFromResource_CheckMethodResult()
    {
        $currencyRatesService = new CurrencyRatesService(new HttpClientMock());
        $date = new \DateTime();
        $rate = $currencyRatesService->getAverageRateFromResources([CbrResource::class, RbcResource::class], 'USD', $date);
        $this->assertEquals(62.71, $rate);
        $rate = $currencyRatesService->getAverageRateFromResources([CbrResource::class, RbcResource::class], 'EUR', $date);
        $this->assertEquals(72.9, $rate);
    }
}
