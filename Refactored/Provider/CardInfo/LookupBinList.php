<?php

namespace TransactionCommission\Provider\CardInfo;

use JsonException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\{ClientExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface};
use TransactionCommission\Card;
use TransactionCommission\CardInterface;
use TransactionCommission\Provider\CardInfoProviderInterface;
use TransactionCommission\Provider\Exception\CardInfoProviderException;


class LookupBinList implements CardInfoProviderInterface
{
    /**
     * @const string
     */
    const API_URL = 'https://lookup.binlist.net/';

    /**
     * @param int $cardBin
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function requestApi(int $cardBin): string
    {
        $client = HttpClient::create();
        $response = $client->request('GET', self::API_URL . $cardBin,
            ['headers' => [
                'Accept' => 'application/json',
            ]]
        );
        return $response->getContent();
    }

    /**
     * @param string $response
     * @return CardInterface
     * @throws CardInfoProviderException
     */
    protected function transformApiResponseToCard(string $response): CardInterface
    {
        try {
            $responseAsObject = json_decode($response, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new CardInfoProviderException('Could not get or parse response from API.');
        }
        if (empty($responseAsObject->country->alpha2)) {
            throw new CardInfoProviderException(
                sprintf('Could not find country code in response from API. Response was %s', $response)
            );
        }

        return new Card($responseAsObject->country->alpha2);
    }

    /**
     * @param int $cardBin
     * @return CardInterface
     * @throws CardInfoProviderException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCard(int $cardBin): CardInterface
    {
        $response = $this->requestApi($cardBin);
        return $this->transformApiResponseToCard($response);
    }
}