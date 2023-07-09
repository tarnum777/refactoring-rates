<?php

namespace TransactionCommission;

class Transaction implements TransactionInterface
{
    /**
     * @var int
     */
    private int $cardBin;
    /**
     * @var float
     */
    private float $amount;
    /**
     * @var string
     */
    private string $currencyCode;

    /**
     * Transaction constructor.
     * @param int $cardBin
     * @param float $amount
     * @param string $currencyCode
     */
    public function __construct(int $cardBin, float $amount, string $currencyCode)
    {
        $this->amount = $amount;
        $this->cardBin = $cardBin;
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return int
     */
    public function getCardBin(): int
    {
        return $this->cardBin;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}