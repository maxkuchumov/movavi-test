<?php


namespace MovaviTest\Clients;

use Movavi\Clients\ClientInterface;
use Movavi\Resources\CbrResource;
use Movavi\Resources\RbcResource;

class HttpClientMock implements ClientInterface
{
    public function getData(string $url): string
    {
        if (preg_match("@cbr.ru@ui", $url)) {
            $usdCode = CbrResource::CURRENCY_CODES_MAPPING['USD'];
            $euroCode = CbrResource::CURRENCY_CODES_MAPPING['EUR'];
            if (preg_match("@$usdCode@ui", $url))  {

                return '<?xml version="1.0" encoding="windows-1251"?>
                    <ValCurs ID="R01235" DateRange1="18.02.2020" DateRange2="18.02.2020" name="Foreign Currency Market Dynamic">
                      <Record Date="18.02.2020" Id="R01235">
                        <Nominal>1</Nominal>
                        <Value>63,3085</Value>
                      </Record>
                    </ValCurs>';
            } else if (preg_match("@$euroCode@ui", $url)) {

                return '<?xml version="1.0" encoding="windows-1251"?>
                    <ValCurs ID="R01235" DateRange1="18.02.2020" DateRange2="18.02.2020" name="Foreign Currency Market Dynamic">
                      <Record Date="18.02.2020" Id="R01235">
                        <Nominal>1</Nominal>
                        <Value>72,6931</Value>
                      </Record>
                    </ValCurs>';
            }

        } else if (preg_match("@rbc.ru@ui", $url)) {
            $usdCode = RbcResource::CURRENCY_CODES_MAPPING['USD'];
            $euroCode = RbcResource::CURRENCY_CODES_MAPPING['EUR'];
            if (preg_match("@$usdCode@ui", $url)) {

                return '{"status": 200, "meta": {"sum_deal": 1.0, "source": "cbrf", "currency_from": "USD", "date": "2020-02-18", "currency_to": "RUR"}, "data": {"date": "2020-02-18", "sum_result": 62.1115, "rate1": 62.1115, "rate2": 0.0158}}';
            } else if (preg_match("@$euroCode@ui", $url)) {

                return '{"status": 200, "meta": {"sum_deal": 1.0, "source": "cbrf", "currency_from": "EUR", "date": "2020-02-18", "currency_to": "RUR"}, "data": {"date": "2020-02-18", "sum_result": 73.1069, "rate1": 73.1069, "rate2": 0.0158}}';
            }
        }

        return '';
    }
}