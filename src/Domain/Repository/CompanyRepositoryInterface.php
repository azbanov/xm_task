<?php

declare(strict_types=1);

namespace XM\Domain\Repository;

use XM\Domain\Exception\RepositoryException;

interface CompanyRepositoryInterface
{
    /**
     * @throws RepositoryException
     */
    public function getAllCompanies(): array;
}
