<?php

declare(strict_types=1);

namespace XM\Infrastructure\Repository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use XM\Domain\Exception\RepositoryException;
use XM\Domain\Repository\CompanyRepositoryInterface;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function __construct(
        private readonly string $url,
        private readonly HttpClientInterface $client
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getAllCompanies(): array
    {
        try {
            $response = $this->client->request(
                Request::METHOD_GET,
                $this->url
            );

            return $response->toArray();
        } catch (\Throwable $e) {
            throw RepositoryException::create('Error occurred on retrieving companies', $e);
        }
    }
}
