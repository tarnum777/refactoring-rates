<?php

namespace TransactionCommission;

interface CurrencyInterface
{
    /**
     * @return string
     */
    public function getCode(): string;
}