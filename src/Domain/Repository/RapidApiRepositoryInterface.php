<?php

declare(strict_types=1);

namespace XM\Domain\Repository;

use XM\Domain\Exception\RepositoryException;

interface RapidApiRepositoryInterface
{
    /**
     * @throws RepositoryException
     */
    public function getQuotesByCompanies(string $companySymbol): array;
}
