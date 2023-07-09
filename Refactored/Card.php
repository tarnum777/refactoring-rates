<?php

namespace TransactionCommission;

class Card implements CardInterface
{
    /**
     * @var string
     */
    private string $countryCode;

    /**
     * Card constructor.
     * @param string $countryCode
     */
    public function __construct(string $countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }
}