<?php

namespace TransactionCommission\Test;

use PHPUnit\Framework\TestCase;
use TransactionCommission\Transaction;

class TransactionTest extends TestCase
{
    /**
     * @covers Transaction::getCardBin
     */
    public function testGetBin()
    {
        $bin = 4745030;
        $amount = 2000.00;
        $currency = 'GBP';
        $testedClassInstance = new Transaction($bin, $amount, $currency);
        $this->assertEquals(4745030, $testedClassInstance->getCardBin());
    }

    /**
     * @covers Transaction::getAmount
     */
    public function testGetAmount()
    {
        $bin = 4745030;
        $amount = 2000.00;
        $currency = 'GBP';
        $testedClassInstance = new Transaction($bin, $amount, $currency);
        $this->assertEquals(2000.00, $testedClassInstance->getAmount());
    }

    /**
     * @covers Transaction::getCurrencyCode
     */
    public function testGetCurrencyCode()
    {
        $bin = 4745030;
        $amount = 2000.00;
        $currency = 'GBP';
        $testedClassInstance = new Transaction($bin, $amount, $currency);
        $this->assertEquals('GBP', $testedClassInstance->getCurrencyCode());
    }
}