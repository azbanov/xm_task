<?php

declare(strict_types=1);

namespace XM\Domain\Facade;

use Psr\Log\LoggerInterface;
use XM\Domain\Service\CompanySymbolsFileService;
use XM\Domain\Service\MailService;
use XM\Domain\Service\QuotesService;

class FormDataHandlerFacade
{
    public function __construct(
        private readonly QuotesService $quotesService,
        private readonly CompanySymbolsFileService $fileService,
        private readonly MailService $mailService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function handleData(array $data): array
    {
        try {
            $response = [];
            $response['error'] = null;
            $response['quotes'] = $this->quotesService->getQuotes($data['symbol'], $data['start_date'], $data['end_date']);
            $response['company_name'] = $this->fileService->getCompanyBySymbol($data['symbol']);

            $this->mailService->sendEmail($data['email'], $response['company_name'], $data['start_date'], $data['end_date']);

            return $response;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            $response['quotes'] = null;
            $response['error'] = 'Internal Error occurred, please contact developers';

            return $response;
        }
    }
}
