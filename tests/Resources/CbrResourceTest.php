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

}
