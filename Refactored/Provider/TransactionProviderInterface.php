<?php

namespace TransactionCommission\Provider;

interface TransactionProviderInterface
{
    /**
     * @return array
     */
    public function provide(): array;
}