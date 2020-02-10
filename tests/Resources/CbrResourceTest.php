<?php

namespace MovaviTest\Tests;

use PHPUnit\Framework\TestCase;
use MovaviTest\Resources\CbrResource;
use MovaviTest\Exceptions\NonRateException;

class CbrResourceTest extends TestCase
{

    public function test_GetRate_returnPositiveFloatRate()
    {
        $date = new \DateTime('08.02.2020');
        $cbrResource = new CbrResource();

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
        $cbrResource = new CbrResource();

        $cbrResource->getRate('UNKNOWN', $date);
    }

    /**
     * @expectedException MovaviTest\Exceptions\NonRateException
     */
    public function test_GetRate_ThrowNonRateException()
    {
        $date = new \DateTime('+1 year');
        $cbrResource = new CbrResource();

        $cbrResource->getRate('USD', $date);
    }

    /**
     * @expectedException MovaviTest\Exceptions\NonRateException
     */
    public function test_parseXmlResponse_TrowNonRateException()
    {
        // incorrect xml (wrong <NOValue> teg)
        $testXml = '<ValCurs ID="R01235" DateRange1="14.03.2001" DateRange2="14.03.2001" name="Foreign Currency Market Dynamic">
                      <Record Date="14.03.2001" Id="R01235">
                        <Nominal>1</Nominal>
                        <NOValue>28,6500</NOValue>
                      </Record>
                     </ValCurs>';

        $class = new \ReflectionClass(CbrResource::class);
        $method = $class->getMethod('parseXmlResponse');
        $method->setAccessible(true);
        $rbcObj = new CbrResource();
        $method->invoke($rbcObj, $testXml);
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
        $rbcObj = new CbrResource();
        $rate = $method->invoke($rbcObj, $testXml);
        $this->assertEquals(28.65, $rate);
    }
}
