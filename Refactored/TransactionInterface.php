<?php

namespace TransactionCommission;

interface TransactionInterface
{
    /**
     * @return int
     */
    public function getCardBin(): int;

    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @return string
     */
    public function getCurrencyCode(): string;
}