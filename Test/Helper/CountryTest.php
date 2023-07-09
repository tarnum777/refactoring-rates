<?php

namespace TransactionCommission\Test;

use PHPUnit\Framework\TestCase;
use TransactionCommission\Helper\Country;

class CountryTest extends TestCase
{
    /**
     * @covers Country::isEuropeanCountry
     */
    public function testGetBin()
    {
        $this->assertEquals(false, Country::isEuropeanCountry('US'));
        $this->assertEquals(true, Country::isEuropeanCountry('DE'));
    }
}