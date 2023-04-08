<?php

declare(strict_types=1);

namespace XM\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

class RepositoryException extends \Exception
{
    private array $context;

    public function getContext(): array
    {
        return $this->context;
    }

    public static function create(string $message, \Throwable $previous = null, array $context = []): self
    {
        $self = new self($message, Response::HTTP_INTERNAL_SERVER_ERROR, $previous);
        $self->context = $context;

        return $self;
    }
}
