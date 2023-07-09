<?php

namespace TransactionCommission\Test\Provider\CardInfo;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use TransactionCommission\CardInterface;
use TransactionCommission\Provider\CardInfo\LookupBinList;
use TransactionCommission\Provider\Exception\CardInfoProviderException;

class LookupBinListTest extends TestCase
{
    /**
     * @var ReflectionMethod
     */
    private ReflectionMethod $transformApiResponseToCardMethod;

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        $testedClass = new ReflectionClass(LookupBinList::class);
        $this->transformApiResponseToCardMethod = $testedClass->getMethod('transformApiResponseToCard');
        $this->transformApiResponseToCardMethod->setAccessible(true);
    }

    /**
     * @covers LookupBinList::transformApiResponseToCard
     */
    public function testTransformApiResponseToCard()
    {
        $apiResponse = '{
                          "number": {
                            "length": 16,
                            "luhn": true
                          },
                          "scheme": "visa",
                          "type": "debit",
                          "brand": "Traditional",
                          "prepaid": null,
                          "country": {
                            "numeric": "826",
                            "alpha2": "GB",
                            "name": "United Kingdom of Great Britain and Northern Ireland",
                            "emoji": "🇬🇧",
                            "currency": "GBP",
                            "latitude": 54,
                            "longitude": -2
                          },
                          "bank": {}
                        }';
        /** @var CardInterface $card */
        $card = $this->transformApiResponseToCardMethod->invokeArgs(new LookupBinList(), [$apiResponse]);
        $this->assertEquals('GB', $card->getCountryCode());
    }

    /**
     * @covers LookupBinList::transformApiResponseToCard
     */
    public function testTransformApiResponseToCardNoInternet()
    {
        $apiResponse = '';
        $this->expectException(CardInfoProviderException::class);
        $this->transformApiResponseToCardMethod->invokeArgs(new LookupBinList(), [$apiResponse]);
    }

    /**
     * @covers LookupBinList::transformApiResponseToCard
     */
    public function testTransformApiResponseNoCountry()
    {
        $apiResponse = '
                          "number": {
                            "length": 16,
                            "luhn": true
                          },
                          "scheme": "visa",
                          "type": "debit",
                          "brand": "Traditional",
                          "prepaid": null,
                          "bank": {}
                        }';
        $this->expectException(CardInfoProviderException::class);
        $this->transformApiResponseToCardMethod->invokeArgs(new LookupBinList(), [$apiResponse]);
    }
}