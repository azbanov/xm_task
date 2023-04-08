<?php

declare(strict_types=1);

namespace Domain\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XM\Domain\Service\CompanySymbolsFileService;
use XM\Infrastructure\Repository\CompanyRepository;

class CompanySymbolsFileServiceTest extends TestCase
{
    private CompanyRepository|MockObject $repository;
    private CompanySymbolsFileService|MockObject $fileService;

    protected function setUp(): void
    {
        $this->repository = $this->getMockBuilder(CompanyRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fileService = new CompanySymbolsFileService('2', $this->repository);
    }

//    /**
//     * @test
//     */
//    public function getCompanySymbols_happyPath_returnsArray()
//    {
//        $dataDir = '../../data_dir';
//        $data = json_encode(JsonDataProvider::getJsonCompanySymbols());
//
//        $fileExists = $this->getFunctionMock(__NAMESPACE__, 'file_exists');
//        $fileExists->expects($this->once())
//            ->with($dataDir)
//            ->willReturn(true);
//
//        $fileGetContents = $this->getFunctionMock(__NAMESPACE__, 'file_get_contents');
//        $fileGetContents->expects($this->once())
//            ->with($dataDir)
//            ->willReturn($data);
//
//        $dateSub = $this->getFunctionMock(__NAMESPACE__, 'date_sub');
//        $dateSub->expects($this->once())
//            ->willReturn('2023-04-07');
//
//        $res = $this->fileService->getCompanySymbols();
//        echo "<pre>";
//        var_dump($res);
//        die("dump");
//    }

    /**
     * @test
     */
    public function isFileExpiredHappyPathReturnsFalse()
    {
        $created = new \DateTime();
        $class = new \ReflectionClass(CompanySymbolsFileService::class);
        $method = $class->getMethod('isFileExpired');
        $method->setAccessible(true);
        $res = $method->invokeArgs($this->fileService, [$created->format('Y-m-d'), new \DateTime()]);

        $this->assertFalse($res);
    }

    /**
     * @test
     */
    public function isFileExpiredHappyPathReturnsTrue()
    {
        $created = '2022-08-17';
        $class = new \ReflectionClass(CompanySymbolsFileService::class);
        $method = $class->getMethod('isFileExpired');
        $method->setAccessible(true);
        $res = $method->invokeArgs($this->fileService, [$created, new \DateTime()]);

        $this->assertTrue($res);
    }
}
