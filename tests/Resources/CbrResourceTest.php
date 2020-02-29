<?php

namespace MovaviTest\Resources;

use PHPUnit\Framework\TestCase;
use Movavi\Resources\CbrResource;
use MovaviTest\Clients\HttpClientMock;
use MovaviTest\Clients\HttpClientBadDataMock;

class CbrResourceTest extends TestCase
{
    /**
     * @expectedException Movavi\Exceptions\UnsupportedCurrencyCodeException
     */
    public function test_GetRate_ThrowUnsupportedCurrencyCodeException()
    {
        $date = new \DateTime();
        $cbrResource = new CbrResource(new HttpClientMock());

        $cbrResource->getRate('UNKNOWN', $date);
    }

    /**
     * @expectedException Movavi\Exceptions\NonRateException
     */
    public function test_GetRate_ThrowNonRateException()
    {
        $date = new \DateTime();
        $cbrResource = new CbrResource(new HttpClientBadDataMock());

        $cbrResource->getRate('USD', $date);
    }

    /**
     * @expectedException Movavi\Exceptions\NonRateException
     */
    public function test_parseXmlResponse_TrowNonRateException()
    {
        // incorrect xml (wrong <NOValue> tag)
        $testXml = '<ValCurs ID="R01235" DateRange1="14.03.2001" DateRange2="14.03.2001" name="Foreign Currency Market Dynamic">
                      <Record Date="14.03.2001" Id="R01235">
                        <Nominal>1</Nominal>
                        <NOValue>28,6500</NOValue>
                      </Record>
                     </ValCurs>';

        $class = new \ReflectionClass(CbrResource::class);
        $method = $class->getMethod('parseXmlResponse');
        $method->setAccessible(true);
        $cbrObj = new CbrResource(new HttpClientMock());
        $method->invoke($cbrObj, $testXml);
    }

    public function test_parseXmlResponse_CheckReturnValue()
    {
        // example of correct xml response
        $testXml = '<ValCurs ID="R01235" DateRange1="14.03.2001" DateRange2="14.03.2001" name="Foreign Currency Market Dynamic">
                      <Record Date="14.03.2001" Id="R01235">
                        <Nominal>1</Nominal>
                        <Value>28,6500</Value>
                      </Record>
                     </ValCurs>';

        $class = new \ReflectionClass(CbrResource::class);
        $method = $class->getMethod('parseXmlResponse');
        $method->setAccessible(true);
        $cbrObj = new CbrResource(new HttpClientMock());
        $rate = $method->invoke($cbrObj, $testXml);
        $this->assertEquals(28.65, $rate);
    }
}
