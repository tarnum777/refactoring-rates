<?php

namespace TransactionCommission\Provider;

use TransactionCommission\CardInterface;

interface CardInfoProviderInterface
{
    /**
     * @param int $cardBin
     * @return CardInterface
     */
    public function getCard(int $cardBin): CardInterface;
}