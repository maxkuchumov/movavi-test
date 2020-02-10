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


}
