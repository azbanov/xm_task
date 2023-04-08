<?php

declare(strict_types=1);

namespace XM\Domain\Service;

use XM\Domain\Exception\QuotesException;
use XM\Domain\Repository\RapidApiRepositoryInterface;

class QuotesService
{
    public function __construct(
        private readonly RapidApiRepositoryInterface $rapidApiRepository
    ) {
    }

    /**
     * @throws QuotesException
     */
    public function getQuotes(string $companySymbol, string $startDate, string $endDate): array
    {
        try {
            $quotes = $this->rapidApiRepository->getQuotesByCompanies($companySymbol);
            $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $startDate.' 00:00:00');
            $endDate = \DateTime::createFromFormat('Y-m-d H:i:s', $endDate.' 23:59:59');

            if ($startDate && $endDate) {
                return $this->filterQuotesByDateRange($quotes, $startDate->getTimestamp(), $endDate->getTimestamp());
            } else {
                throw new QuotesException('Error occurred');
            }
        } catch (\Throwable $e) {
            throw new QuotesException($e->getMessage());
        }
    }

    private function filterQuotesByDateRange(array $quotes, int $startDate, int $endDate): array
    {
        $res = [];

        foreach ($quotes as $quote) {
            if ($quote['date'] >= $startDate && $quote['date'] <= $endDate) {
                $res[] = $quote;
            }
        }

        return array_reverse($res);
    }
}
