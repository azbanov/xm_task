<?php

declare(strict_types=1);

namespace XM\Domain\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DateValidator
{
    public static function validateStartDate(mixed $object, ExecutionContextInterface $context): void
    {
        $endDate = $context
            ->getRoot()
            ->getData()['end_date'];

        $currentDate = new \DateTime();
        $currentDate = $currentDate->format('Y-m-d');

        if ($object > $currentDate) {
            $context->buildViolation('Start Date should be equal or less than current date.')
                ->atPath('start_date')
                ->addViolation();
        }

        if ($endDate && $object > $endDate) {
            $context->buildViolation('Start Date should be equal or less than End Date date.')
                ->atPath('start_date')
                ->addViolation();
        }
    }

    public static function validateEndDate(mixed $object, ExecutionContextInterface $context): void
    {
        $currentDate = new \DateTime();
        $currentDate = $currentDate->format('Y-m-d');

        if ($object > $currentDate) {
            $context->buildViolation('End Date should be equal or less than current date.')
                ->atPath('end_date')
                ->addViolation();
        }
    }
}
