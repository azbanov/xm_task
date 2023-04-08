<?php

declare(strict_types=1);

namespace Domain\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XM\Domain\Exception\QuotesException;
use XM\Domain\Exception\RepositoryException;
use XM\Domain\Repository\RapidApiRepositoryInterface;
use XM\Domain\Service\QuotesService;
use XM\Infrastructure\Repository\RapidApiRepository;
use XM\Tests\DataProvider\JsonDataProvider;

class QuotesServiceTest extends TestCase
{
    private RapidApiRepositoryInterface|MockObject $repository;
    private QuotesService|MockObject $service;

    protected function setUp(): void
    {
        $this->repository = $this->getMockBuilder(RapidApiRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = new QuotesService($this->repository);
    }

    /**
     * @test
     */
    public function getQuotesHappyPathReturnsQuotesArray()
    {
        $quotesJson = JsonDataProvider::getJsonQuotes();
        $startDate = '2023-04-03';
        $endDate = '2023-04-07';

        $this->repository->expects($this->once())
            ->method('getQuotesByCompanies')
            ->willReturn($quotesJson['prices']);

        $res = $this->service->getQuotes('GOOG', $startDate, $endDate);

        $this->assertIsArray($res);
        $this->assertCount(4, $res);
    }

    /**
     * @test
     */
    public function getQuotesRepositoryErrorThrowsQuotesException()
    {
        $startDate = '2023-04-03';
        $endDate = '2023-04-07';

        $this->repository->expects($this->once())
            ->method('getQuotesByCompanies')
            ->willThrowException(RepositoryException::create('repo error'));

        $this->expectException(QuotesException::class);

        $this->service->getQuotes('GOOG', $startDate, $endDate);
    }
}
