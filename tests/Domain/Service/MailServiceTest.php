<?php

declare(strict_types=1);

namespace Domain\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use XM\Domain\Exception\MailServiceException;
use XM\Domain\Service\MailService;

class MailServiceTest extends TestCase
{
    private MailerInterface|MockObject $mailer;
    private MailService|MockObject $service;

    protected function setUp(): void
    {
        $this->mailer = $this->getMockBuilder(MailerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = new MailService('test@test.com', $this->mailer);
    }

    /**
     * @test
     */
    public function sendEmailHappyPathSendsEmail()
    {
        $this->mailer->expects($this->once())
            ->method('send');

        $this->service->sendEmail('to@to.to', 'GOOG', '2023-04-07', '2023-04-07');
    }

    /**
     * @test
     */
    public function sendEmailMailerErrorThrowsMailServiceException()
    {
        $this->mailer->expects($this->once())
            ->method('send')
            ->willThrowException(new \Exception());

        $this->expectException(MailServiceException::class);

        $this->service->sendEmail('to@to.to', 'GOOG', '2023-04-07', '2023-04-07');
    }
}
