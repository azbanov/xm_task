<?php

declare(strict_types=1);

namespace XM\Infrastructure\Repository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use XM\Domain\Exception\RepositoryException;
use XM\Domain\Repository\RapidApiRepositoryInterface;

class RapidApiRepository implements RapidApiRepositoryInterface
{
    private const RAPID_KEY_HEADER = 'X-RapidAPI-Key';

    public function __construct(
        private readonly string $rapidUrl,
        private readonly string $rapidKey,
        private readonly HttpClientInterface $client
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getQuotesByCompanies(string $companySymbol): array
    {
        try {
            $response = $this->client->request(
                Request::METHOD_GET,
                $this->rapidUrl,
                [
                    'query' => [
                        'symbol' => $companySymbol,
                    ],
                    'headers' => [
                        self::RAPID_KEY_HEADER => $this->rapidKey,
                    ],
                ],
            );

            return $response->toArray()['prices'];
        } catch (\Throwable $e) {
            throw RepositoryException::create(sprintf('Error during retrieving quotes for company %s : %s', $companySymbol, $e->getMessage()), $e);
        }
    }
}
