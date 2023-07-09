<?php

namespace TransactionCommission\Test;

use PHPUnit\Framework\TestCase;
use TransactionCommission\Card;
use TransactionCommission\Rate;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use TransactionCommission\CommissionCalculator;
use TransactionCommission\Provider\CardInfo\LookupBinList as CardInfoProvider;
use TransactionCommission\Provider\RateInfo\ExchangeRatesApi as RateInfoProvider;
use TransactionCommission\Transaction;

class CommissionCalculatorTest extends TestCase
{
    /**
     * @var ReflectionMethod
     */
    private ReflectionMethod $getCommissionCoefficientMethod;

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        $testedClass = new ReflectionClass(CommissionCalculator::class);
        $this->getCommissionCoefficientMethod = $testedClass->getMethod('getCommissionCoefficient');
        $this->getCommissionCoefficientMethod->setAccessible(true);
    }

    /**
     * @covers CommissionCalculator::getCommissionCoefficient
     */
    public function testGetCommissionCoefficientEUCard()
    {
        $card = $this->createMock(Card::class);
        $card->expects($this->any())
            ->method('getCountryCode')
            ->will($this->returnValue('LT'));
        $cardInfoProvider = $this->createMock(CardInfoProvider::class);
        $rateInfoProvider = $this->createMock(RateInfoProvider::class);
        $testedClassInstance = new CommissionCalculator($cardInfoProvider, $rateInfoProvider);
        $this->assertEquals(0.01, $this->getCommissionCoefficientMethod->invokeArgs($testedClassInstance, [$card]));
    }

    /**
     * @covers CommissionCalculator::getCommissionCoefficient
     */
    public function testGetCommissionCoefficientNonEUCard()
    {
        $card = $this->createMock(Card::class);
        $card->expects($this->any())
            ->method('getCountryCode')
            ->will($this->returnValue('US'));
        $cardInfoProvider = $this->createMock(CardInfoProvider::class);
        $rateInfoProvider = $this->createMock(RateInfoProvider::class);
        $testedClassInstance = new CommissionCalculator($cardInfoProvider, $rateInfoProvider);
        $this->assertEquals(0.02, $this->getCommissionCoefficientMethod->invokeArgs($testedClassInstance, [$card]));
    }

    /**
     * @covers CommissionCalculator::calculate
     */
    public function testCalculateNonEuCardCommission()
    {
        $card = $this->createMock(Card::class);
        $card->expects($this->any())
            ->method('getCountryCode')
            ->will($this->returnValue('CA'));
        $cardInfoProvider = $this->createMock(CardInfoProvider::class);
        $cardInfoProvider->expects($this->any())
            ->method('getCard')
            ->will($this->returnValue($card));
        $rate = $this->createMock(Rate::class);
        $rate->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue(1.5634));
        $rateInfoProvider = $this->createMock(RateInfoProvider::class);
        $rateInfoProvider->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue($rate));

        $transaction = new Transaction(7777777777, 500.00, 'CAD');
        $testedClassInstance = new CommissionCalculator($cardInfoProvider, $rateInfoProvider);
        $this->assertEquals(6.4, $testedClassInstance->calculate($transaction));
    }

    /**
     * @covers CommissionCalculator::calculate
     */
    public function testCalculateEuCardEURCommission()
    {
        $card = $this->createMock(Card::class);
        $card->expects($this->any())
            ->method('getCountryCode')
            ->will($this->returnValue('DE'));
        $cardInfoProvider = $this->createMock(CardInfoProvider::class);
        $cardInfoProvider->expects($this->any())
            ->method('getCard')
            ->will($this->returnValue($card));
        $rate = $this->createMock(Rate::class);
        $rate->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue(1));
        $rateInfoProvider = $this->createMock(RateInfoProvider::class);
        $rateInfoProvider->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue($rate));

        $transaction = new Transaction(7777777777, 500.00, 'EUR');
        $testedClassInstance = new CommissionCalculator($cardInfoProvider, $rateInfoProvider);
        $this->assertEquals(5, $testedClassInstance->calculate($transaction));
    }

    /**
     * @covers CommissionCalculator::calculate
     */
    public function testCalculateEuCardUSDCommission()
    {
        $card = $this->createMock(Card::class);
        $card->expects($this->any())
            ->method('getCountryCode')
            ->will($this->returnValue('CZ'));
        $cardInfoProvider = $this->createMock(CardInfoProvider::class);
        $cardInfoProvider->expects($this->any())
            ->method('getCard')
            ->will($this->returnValue($card));
        $rate = $this->createMock(Rate::class);
        $rate->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue(26.66));
        $rateInfoProvider = $this->createMock(RateInfoProvider::class);
        $rateInfoProvider->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue($rate));

        $transaction = new Transaction(7777777777, 500.00, 'CZK');
        $testedClassInstance = new CommissionCalculator($cardInfoProvider, $rateInfoProvider);
        $this->assertEquals(0.19, $testedClassInstance->calculate($transaction));
    }
}