<?php

namespace TransactionCommission\Provider\RateInfo;

use JsonException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\{ClientExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface};
use TransactionCommission\{Currency,
    CurrencyInterface,
    Provider\Exception\RateInfoProviderException,
    Provider\RateInfoProviderInterface,
    Rate,
    RateInterface};

class ExchangeRatesApi implements RateInfoProviderInterface
{
    /**
     * @const string
     */
    const API_URL = 'https://api.exchangeratesapi.io/latest';
    /**
     * @const string
     */
    const API_DEFAULT_TO_CURRENCY = 'EUR';

    /**
     * @return CurrencyInterface
     */
    public function getDefaultToCurrency(): CurrencyInterface
    {
        return new Currency(self::API_DEFAULT_TO_CURRENCY);
    }

    /**
     * @param CurrencyInterface $fromCurrency
     * @param CurrencyInterface|null $toCurrency
     * @return RateInterface
     * @throws ClientExceptionInterface
     * @throws JsonException
     * @throws RateInfoProviderException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getRate(CurrencyInterface $fromCurrency, CurrencyInterface $toCurrency = null): RateInterface
    {
        $toCurrency = $toCurrency ?? $this->getDefaultToCurrency();
        $response = $this->requestApi($toCurrency);
        return $this->transformApiResponseToRate($response, $fromCurrency, $toCurrency);
    }

    /**
     * @param string $response
     * @param CurrencyInterface $fromCurrency
     * @param CurrencyInterface $toCurrency
     * @return RateInterface
     * @throws RateInfoProviderException
     */
    protected function transformApiResponseToRate(string $response,
                                                  CurrencyInterface $fromCurrency,
                                                  CurrencyInterface $toCurrency): RateInterface
    {
        try {
            $responseAsArray = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RateInfoProviderException('Could not get or parse response from API.');
        }
        if (isset($responseAsArray['error']['info'])) {
            throw new RateInfoProviderException(
                sprintf('Could not get rate due to exception:  %s',
                    $responseAsArray['error']['info'])
            );
        }
        $fromCurrencyCode = $fromCurrency->getCode();
        if ($fromCurrencyCode === $responseAsArray['base']) {
            return new Rate(1, $fromCurrency, $toCurrency);
        } elseif (empty($responseAsArray['rates'][$fromCurrencyCode])) {
            throw new RateInfoProviderException(
                sprintf('Could not find rate in response from API. From Currency was %s', $fromCurrencyCode)
            );
        }
        $rateValue = (float)$responseAsArray['rates'][$fromCurrencyCode];
        return new Rate($rateValue, $fromCurrency, $toCurrency);
    }


    /**
     * @param CurrencyInterface $toCurrency
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function requestApi(CurrencyInterface $toCurrency): string
    {
        $client = HttpClient::create();
        $response = $client->request('GET', self::API_URL,
            ['headers' => [
                'Accept' => 'application/json',
            ]]
        );
        return $response->getContent();
    }
}