<?php

namespace TransactionCommission;

interface CardInterface
{
    /**
     * @return string
     */
    public function getCountryCode(): string;
}