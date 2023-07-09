<?php

namespace TransactionCommission\Provider\Transaction\File;

use TransactionCommission\Provider\TransactionProviderException;
use TransactionCommission\Provider\TransactionProviderInterface;
use TransactionCommission\Transaction;

class Json implements TransactionProviderInterface
{
    /**
     * @var string
     */
    private string $filePath;

    /**
     * Json constructor.
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return array
     * @throws TransactionProviderException
     */
    public function provide(): array
    {
        if (!is_file($this->filePath)) {
            throw new TransactionProviderException(sprintf('File %s does not exist.', $this->filePath));
        }
        $transactions = [];
        foreach (file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            try {
                $transactionAsArray = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
                $transactions[] = new Transaction((int)$transactionAsArray['bin'], (float)$transactionAsArray['amount'],
                    $transactionAsArray['currency']);
            } catch (\JsonException $exception) {
                throw new TransactionProviderException('Could not get or parse transactions from file.');
            }
        }

        return $transactions;
    }
}