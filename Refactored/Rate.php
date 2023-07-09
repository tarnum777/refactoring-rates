<?php

namespace TransactionCommission;

class Rate implements RateInterface
{
    /**
     * @var float
     */
    private float $rate;
    /**
     * @var CurrencyInterface
     */
    private CurrencyInterface $fromCurrency;
    /**
     * @var CurrencyInterface
     */
    private CurrencyInterface $toCurrency;

    public function __construct(float $rate, CurrencyInterface $fromCurrency, CurrencyInterface $toCurrency)
    {
        $this->rate = $rate;
        $this->fromCurrency = $fromCurrency;
        $this->toCurrency = $toCurrency;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @return CurrencyInterface
     */
    public function getFromCurrency(): CurrencyInterface
    {
        return $this->fromCurrency;
    }

    /**
     * @return CurrencyInterface
     */
    public function getToCurrency(): CurrencyInterface
    {
        return $this->toCurrency;
    }
}