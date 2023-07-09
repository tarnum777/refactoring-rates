<?php

namespace TransactionCommission\Test;

use PHPUnit\Framework\TestCase;
use TransactionCommission\Card;

class CardTest extends TestCase
{
    /**
     * @covers Card::getCountryCode
     */
    public function testGetCountryCode()
    {
        $countryCode = 'UA';
        $testedClassInstance = new Card($countryCode);
        $this->assertEquals('UA', $testedClassInstance->getCountryCode());
    }
}