<?php

require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use TransactionCommission\Provider\Transaction\File\Json as FileJsonTransactionProvider;
use TransactionCommission\Provider\CardInfo\LookupBinList as CardInfoProvider;
use TransactionCommission\Provider\RateInfo\ExchangeRatesApi as RateInfoProvider;
use TransactionCommission\CommissionCalculator;

$filePath = $argv[1] ?? dirname(__FILE__)  . DIRECTORY_SEPARATOR . 'Original/input.txt';

try{
    $transactionsProvider = new FileJsonTransactionProvider($filePath);
    $commissionCalculator = new CommissionCalculator(new CardInfoProvider(), new RateInfoProvider());
    foreach ($transactionsProvider->provide() as $transaction) {
        echo $commissionCalculator->calculate($transaction);
        echo PHP_EOL;
    }
} catch (Exception $exception) {
    echo $exception->getMessage();
    echo PHP_EOL;
}
