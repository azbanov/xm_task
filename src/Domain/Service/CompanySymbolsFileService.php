<?php

declare(strict_types=1);

namespace XM\Domain\Service;

use XM\Domain\Exception\CompanySymbolsFileException;
use XM\Domain\Exception\RepositoryException;
use XM\Infrastructure\Repository\CompanyRepository;

class CompanySymbolsFileService
{
    private const DATA_DIR = __DIR__.'/../../../data/companySymbols.json';

    public function __construct(
        private readonly string $expirationDays,
        private readonly CompanyRepository $repository
    ) {
    }

    /**
     * @throws CompanySymbolsFileException
     */
    public function getCompanySymbols(): array
    {
        try {
            if ($data = file_get_contents(self::DATA_DIR)) {
                $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

                if ($this->isFileExpired($data['created'], new \DateTime())) {
                    $data = $this->rewriteFile();
                }

                return $data['companies'];
            } else {
                throw new CompanySymbolsFileException('Error occurred on getting company symbols');
            }
        } catch (\Exception $e) {
            throw new CompanySymbolsFileException($e->getMessage());
        }
    }

    private function isFileExpired(string $createdTime, \DateTime $currentDate): bool
    {
        $interval = date_interval_create_from_date_string($this->expirationDays.' days');
        if ($interval) {
            $expiredDate = date_sub($currentDate, $interval);
            $expiredDate = $expiredDate->format('Y-m-d');

            if ($expiredDate <= $createdTime) {
                return false;
            }
        }

        return true;
    }

    /**
     * @throws RepositoryException
     * @throws CompanySymbolsFileException
     * @throws \JsonException
     */
    private function rewriteFile(): array
    {
        $data = [];
        $now = new \DateTime();
        $data['created'] = $now->format('Y-m-d');
        $companies = $this->repository->getAllCompanies();

        foreach ($companies as $company) {
            $data['companies'][$company['Symbol']] = $company['Company Name'];
        }

        if (!file_put_contents(self::DATA_DIR, json_encode($data, JSON_THROW_ON_ERROR))) {
            throw new CompanySymbolsFileException('Error occurred on creating file: '.self::DATA_DIR);
        }

        return $data;
    }

    /**
     * @throws CompanySymbolsFileException
     */
    public function getCompanyBySymbol(string $symbol): string
    {
        $companies = $this->getCompanySymbols();

        if (key_exists($symbol, $companies)) {
            return $companies[$symbol];
        } else {
            throw new CompanySymbolsFileException('Company not found with symbol: '.$symbol);
        }
    }
}
