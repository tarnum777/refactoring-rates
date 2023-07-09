<?php

namespace TransactionCommission\Test\Provider\RateInfo;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use TransactionCommission\Currency;
use TransactionCommission\Provider\Exception\RateInfoProviderException;
use TransactionCommission\Provider\RateInfo\ExchangeRatesApi;
use TransactionCommission\RateInterface;

class ExchangeRatesApiTest extends TestCase
{
    /**
     * @var ReflectionMethod
     */
    private ReflectionMethod $transformApiResponseToRateMethod;

    /**
     * @throws \ReflectionException
     */
    public function setUp(): void
    {
        $testedClass = new \ReflectionClass(ExchangeRatesApi::class);
        $this->transformApiResponseToRateMethod = $testedClass->getMethod('transformApiResponseToRate');
        $this->transformApiResponseToRateMethod->setAccessible(true);
    }

    /**
     * @covers ExchangeRatesApi::transformApiResponseToRate
     */
    public function testTransformApiResponseToRate()
    {
        $apiResponse = '{
                          "rates": {
                            "CAD": 1.5641,
                            "USD": 1.1876,
                            "MXN": 25.1792,
                            "ILS": 4.0807,
                            "GBP": 0.9219,
                            "KRW": 1404.73,
                            "MYR": 4.9232
                          },
                          "base": "EUR",
                          "date": "2020-09-14"
                        }';

        $fromCurrency = $this->createMock(Currency::class);
        $fromCurrency->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('USD'));
        $toCurrency = $this->createMock(Currency::class);
        $toCurrency->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('EUR'));
        $testedClassInstance = new ExchangeRatesApi();
        /** @var RateInterface $rate */
        $rate = $this->transformApiResponseToRateMethod->invokeArgs($testedClassInstance, [$apiResponse, $fromCurrency, $toCurrency]);
        $this->assertEquals('1.1876', $rate->getRate());
    }


    /**
     * @covers ExchangeRatesApi::transformApiResponseToRate
     */
    public function testTransformApiResponseToRateDefaultCurrency()
    {
        $apiResponse = '{
                          "rates": {
                            "CAD": 1.5641,
                            "USD": 1.1876,
                            "MXN": 25.1792,
                            "ILS": 4.0807,
                            "GBP": 0.9219,
                            "KRW": 1404.73,
                            "MYR": 4.9232
                          },
                          "base": "EUR",
                          "date": "2020-09-14"
                        }';

        $fromCurrency = $this->createMock(Currency::class);
        $fromCurrency->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('EUR'));
        $toCurrency = $this->createMock(Currency::class);
        $toCurrency->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('EUR'));
        $testedClassInstance = new ExchangeRatesApi();
        /** @var RateInterface $rate */
        $rate = $this->transformApiResponseToRateMethod->invokeArgs($testedClassInstance,
            [$apiResponse, $fromCurrency, $toCurrency]);
        $this->assertEquals('1', $rate->getRate());
    }

    /**
     * @covers ExchangeRatesApi::transformApiResponseToRate
     */
    public function testTransformApiResponseToRateNoInternet()
    {
        $apiResponse = '';
        $this->expectException(RateInfoProviderException::class);
        $fromCurrency = $this->createMock(Currency::class);
        $fromCurrency->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('EUR'));
        $toCurrency = $this->createMock(Currency::class);
        $toCurrency->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('EUR'));
        $this->transformApiResponseToRateMethod->invokeArgs(new ExchangeRatesApi(),
            [$apiResponse, $fromCurrency, $toCurrency]);
    }

    /**
     * @covers ExchangeRatesApi::transformApiResponseToRate
     */
    public function testTransformApiResponseToRateNoCurrencyInResponse()
    {
        $apiResponse = '{
                          "rates": {
                            "CAD": 1.5641,
                            "USD": 1.1876,
                            "MXN": 25.1792,
                            "ILS": 4.0807,
                            "GBP": 0.9219,
                            "KRW": 1404.73,
                            "MYR": 4.9232
                          },
                          "base": "EUR",
                          "date": "2020-09-14"
                        }';
        $this->expectException(RateInfoProviderException::class);
        $fromCurrency = $this->createMock(Currency::class);
        $fromCurrency->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('UAH'));
        $toCurrency = $this->createMock(Currency::class);
        $toCurrency->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('EUR'));
        $this->transformApiResponseToRateMethod->invokeArgs(new ExchangeRatesApi(),
            [$apiResponse, $fromCurrency, $toCurrency]);
    }
}