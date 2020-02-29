<?php

namespace MovaviTest\Resources;

use PHPUnit\Framework\TestCase;
use Movavi\Resources\RbcResource;
use MovaviTest\Clients\HttpClientMock;
use MovaviTest\Clients\HttpClientBadDataMock;

class RbcResourceTest extends TestCase
{

    /**
     * @expectedException Movavi\Exceptions\NonRateException
     */
    public function test_GetRate_ThrowNonRateException()
    {
        $date = new \DateTime();
        $rbcResource = new RbcResource(new HttpClientBadDataMock());

        $rbcResource->getRate('USD', $date);
    }

    /**
     * @expectedException Movavi\Exceptions\UnsupportedCurrencyCodeException
     */
    public function test_GetRate_ThrowUnsupportedCurrencyCodeException()
    {
        $date = new \DateTime();
        $rbcResource = new RbcResource(new HttpClientMock());

        $rbcResource->getRate('UNKNOWN', $date);
    }

    /**
     * @expectedException Movavi\Exceptions\NonRateException
     */
    public function test_parseJsonResponse_TrowNonRateException()
    {
        $testJson = '{"status": 200, "meta": {"sum_deal": 1.0, "source": "cbrf", "currency_from": "USD", "date": "2028-10-12", "currency_to": "RUR"}, "data": {"date": "2028-10-12", "sum_result_INCORRECT": 63.472, "rate1": 63.472, "rate2": 0.0158}}';
        $class = new \ReflectionClass(RbcResource::class);
        $method = $class->getMethod('parseJsonResponse');
        $method->setAccessible(true);
        $rbcObj = new RbcResource(new HttpClientMock());
        $method->invoke($rbcObj, $testJson);
    }

    public function test_parseJsonResponse_CheckReturnValue()
    {
        $testJson = '{"status": 200, "meta": {"sum_deal": 1.0, "source": "cbrf", "currency_from": "USD", "date": "2028-10-12", "currency_to": "RUR"}, "data": {"date": "2028-10-12", "sum_result": 66.666, "rate1": 63.472, "rate2": 0.0158}}';
        $class = new \ReflectionClass(RbcResource::class);
        $method = $class->getMethod('parseJsonResponse');
        $method->setAccessible(true);
        $rbcObj = new RbcResource(new HttpClientMock());
        $rate = $method->invoke($rbcObj, $testJson);
        $this->assertEquals(66.666, $rate);
    }

}
