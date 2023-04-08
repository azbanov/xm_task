<?php

declare(strict_types=1);

namespace XM\Domain\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use XM\Domain\Exception\MailServiceException;

class MailService
{
    public function __construct(
        private readonly string $emailFrom,
        private readonly MailerInterface $mailer
    ) {
    }

    /**
     * @throws MailServiceException
     */
    public function sendEmail(string $emailTo, string $companyName, string $fromDate, string $toDate): void
    {
        try {
            $email = (new Email())
                ->from($this->emailFrom)
                ->to($emailTo)
                ->subject($companyName)
                ->text("From $fromDate to $toDate");

            $this->mailer->send($email);
        } catch (\Throwable $e) {
            throw new MailServiceException($e->getMessage());
        }
    }
}
