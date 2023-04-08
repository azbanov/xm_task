<?php

declare(strict_types=1);

namespace XM\Tests\Infrastructure\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use XM\Domain\Exception\RepositoryException;
use XM\Infrastructure\Repository\CompanyRepository;
use XM\Tests\DataProvider\JsonDataProvider;

class CompanyRepositoryTest extends TestCase
{
    private const URL = 'https://example.com';

    private HttpClientInterface|MockObject $client;
    private CompanyRepository|MockObject $companyRepository;

    protected function setUp(): void
    {
        $this->client = $this->getMockBuilder(HttpClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyRepository = new CompanyRepository(
            self::URL,
            $this->client
        );
    }

    /**
     * @test
     */
    public function getAllCompaniesHappyPathReturnsCompaniesArary()
    {
        $expected = JsonDataProvider::getJsonCompanies();
        $mockResponse = new MockResponse(json_encode($expected));
        $client = new MockHttpClient($mockResponse, self::URL);
        $repository = new CompanyRepository(self::URL, $client);
        $res = $repository->getAllCompanies();

        $this->assertIsArray($res);
        $this->assertEquals($expected[0]['Company Name'], $res[0]['Company Name']);
        $this->assertEquals(count($expected), count($res));
    }

    /**
     * @test
     */
    public function getAllCompaniesClientErrorThrowsRepositoryException()
    {
        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception());

        $this->expectException(RepositoryException::class);

        $this->companyRepository->getAllCompanies();
    }
}
