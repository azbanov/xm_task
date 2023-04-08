<?php

declare(strict_types=1);

namespace Infrastructure\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use XM\Domain\Exception\RepositoryException;
use XM\Infrastructure\Repository\RapidApiRepository;
use XM\Tests\DataProvider\JsonDataProvider;

class RapidApiRepositoryTest extends TestCase
{
    private const URL = 'https://example.com';
    private const KEY = 'asfasdf2335asfaf';

    private HttpClientInterface|MockObject $client;
    private RapidApiRepository $repository;

    protected function setUp(): void
    {
        $this->client = $this->getMockBuilder(HttpClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repository = new RapidApiRepository(
            self::URL,
            self::KEY,
            $this->client
        );
    }

    /**
     * @test
     */
    public function getQuotesByCompaniesHappyPathReturnsQuotesArray()
    {
        $expected = JsonDataProvider::getJsonQuotes();
        $mockResponse = new MockResponse(json_encode($expected));
        $client = new MockHttpClient($mockResponse, self::URL);
        $repository = new RapidApiRepository(self::URL, self::KEY, $client);
        $res = $repository->getQuotesByCompanies('GOOG');

        $this->assertIsArray($res);
        $this->assertEquals($expected['prices'][0]['date'], $res[0]['date']);
        $this->assertEquals(count($expected['prices']), count($res));
    }

    /**
     * @test
     */
    public function getQuotesByCompaniesClientErrorThrowsRepositoryException()
    {
        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception());

        $this->expectException(RepositoryException::class);

        $this->repository->getQuotesByCompanies('GOOG');
    }
}
