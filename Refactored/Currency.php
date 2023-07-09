<?php

namespace TransactionCommission;

class Currency implements CurrencyInterface
{
    /**
     * @var string
     */
    private string $code;

    /**
     * Currency constructor.
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}