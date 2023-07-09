<?php

namespace TransactionCommission\Provider;

use TransactionCommission\CurrencyInterface;
use TransactionCommission\RateInterface;

interface RateInfoProviderInterface
{
    /**
     * Get $fromCurrencyCode / $toCurrencyCode currencies ratio
     * If currencies are same, return 1
     * @param CurrencyInterface $fromCurrency
     * @param CurrencyInterface $toCurrency
     * @return RateInterface
     */
    public function getRate(CurrencyInterface $fromCurrency, CurrencyInterface $toCurrency): RateInterface;
}