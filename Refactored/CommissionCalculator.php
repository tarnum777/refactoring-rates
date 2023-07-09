<?php

namespace TransactionCommission;

use TransactionCommission\Provider\CardInfoProviderInterface;
use TransactionCommission\Provider\RateInfoProviderInterface;
use TransactionCommission\Helper\Country;

class CommissionCalculator
{
    /**
     * @const string
     */
    const BASIC_CURRENCY_CODE = 'EUR';
    /**
     * @const float
     */
    const EUROPE_COMMISSION_COEFFICIENT = 0.01;
    /**
     * @const float
     */
    const NON_EUROPE_COMMISSION_COEFFICIENT = 0.02;
    /**
     * @var CardInfoProviderInterface
     */
    private CardInfoProviderInterface $cardInfoProvider;
    /**
     * @var RateInfoProviderInterface
     */
    private RateInfoProviderInterface $rateInfoProvider;

    /**
     * CommissionCalculator constructor.
     * @param CardInfoProviderInterface $cardInfoProvider
     * @param RateInfoProviderInterface $rateInfoProvider
     */
    public function __construct(CardInfoProviderInterface $cardInfoProvider, RateInfoProviderInterface $rateInfoProvider)
    {
        $this->cardInfoProvider = $cardInfoProvider;
        $this->rateInfoProvider = $rateInfoProvider;
    }

    /**
     * @param CardInterface $card
     * @return float
     */
    protected function getCommissionCoefficient(CardInterface $card): float
    {
        if (Country::isEuropeanCountry($card->getCountryCode())) {
            return self::EUROPE_COMMISSION_COEFFICIENT;
        }
        return self::NON_EUROPE_COMMISSION_COEFFICIENT;
    }

    /**
     * @param TransactionInterface $transaction
     * @return float
     */
    public function calculate(TransactionInterface $transaction): float
    {
        $card = $this->cardInfoProvider->getCard($transaction->getCardBin());
        $rate = $this->rateInfoProvider->getRate(new Currency($transaction->getCurrencyCode()),
            new Currency(self::BASIC_CURRENCY_CODE));
        $amount = $transaction->getAmount() / $rate->getRate();
        $amount *= $this->getCommissionCoefficient($card);
        return round($amount, 2);
    }
}