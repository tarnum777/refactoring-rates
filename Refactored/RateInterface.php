<?php

namespace TransactionCommission;

interface RateInterface
{
    /**
     * @return float
     */
    public function getRate(): float;

    /**
     * @return CurrencyInterface
     */
    public function getFromCurrency(): CurrencyInterface;

    /**
     * @return CurrencyInterface
     */
    public function getToCurrency(): CurrencyInterface;
}