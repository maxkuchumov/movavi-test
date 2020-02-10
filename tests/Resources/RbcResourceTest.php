<?php

namespace MovaviTest\Tests;

use PHPUnit\Framework\TestCase;
use MovaviTest\Resources\RbcResource;

class RbcResourceTest extends TestCase
{

    public function test_GetRate_returnPositiveFloatRate()
    {
        $date = new \DateTime('08.02.2020');
        $cbrResource = new RbcResource();

        $usdRate = $cbrResource->getRate('USD', $date);
        $this->assertInternalType('float', $usdRate);
        $this->assertGreaterThan(0, $usdRate);

        $eurRate = $cbrResource->getRate('EUR', $date);
        $this->assertInternalType('float', $eurRate);
        $this->assertGreaterThan(0, $eurRate);
    }

    /**
     * @expectedException MovaviTest\Exceptions\UnsupportedCurrencyCodeException
     */
    public function test_GetRate_ThrowUnsupportedCurrencyCodeException()
    {
        $date = new \DateTime('08.02.2020');
        $cbrResource = new RbcResource();

        $cbrResource->getRate('UNKNOWN', $date);
    }

    /**
     * @expectedException MovaviTest\Exceptions\NonRateException
     */
    public function test_parseJsonResponse_TrowNonRateException()
    {
        $testJson = '{"status": 200, "meta": {"sum_deal": 1.0, "source": "cbrf", "currency_from": "USD", "date": "2028-10-12", "currency_to": "RUR"}, "data": {"date": "2028-10-12", "sum_result_INCORRECT": 63.472, "rate1": 63.472, "rate2": 0.0158}}';
        $class = new \ReflectionClass(RbcResource::class);
        $method = $class->getMethod('parseJsonResponse');
        $method->setAccessible(true);
        $rbcObj = new RbcResource();
        $method->invoke($rbcObj, $testJson);
    }

    public function test_parseJsonResponse_CheckReturnValue()
    {
        $testJson = '{"status": 200, "meta": {"sum_deal": 1.0, "source": "cbrf", "currency_from": "USD", "date": "2028-10-12", "currency_to": "RUR"}, "data": {"date": "2028-10-12", "sum_result": 66.666, "rate1": 63.472, "rate2": 0.0158}}';
        $class = new \ReflectionClass(RbcResource::class);
        $method = $class->getMethod('parseJsonResponse');
        $method->setAccessible(true);
        $rbcObj = new RbcResource();
        $rate = $method->invoke($rbcObj, $testJson);
        $this->assertEquals(66.666, $rate);
    }

}
