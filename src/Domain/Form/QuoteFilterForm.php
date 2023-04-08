<?php

declare(strict_types=1);

namespace XM\Domain\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use XM\Domain\Exception\CompanySymbolsFileException;
use XM\Domain\Service\CompanySymbolsFileService;
use XM\Domain\Validator\DateValidator;

class QuoteFilterForm extends AbstractType
{
    public function __construct(
        private readonly CompanySymbolsFileService $fileService
    ) {
    }

    /**
     * @throws CompanySymbolsFileException
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('symbol', ChoiceType::class, [
                'required' => true,
                'choices' => $this->getSymbolsArr(),
                'constraints' => [new NotNull()],
            ])
            ->add('start_date', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/',
                        'message' => 'This value is not a valid date.',
                    ]),
                    new Callback([
                        DateValidator::class,
                        'validateStartDate',
                    ]),
                    new NotNull(),
                ],
            ])
            ->add('end_date', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/',
                        'message' => 'This value is not a valid date.',
                    ]),
                    new Callback([
                        DateValidator::class,
                        'validateEndDate',
                    ]),
                    new NotNull(),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new Email(),
                ],
            ])
            ->add('submit', SubmitType::class);
    }

    /**
     * @throws CompanySymbolsFileException
     */
    private function getSymbolsArr(): array
    {
        $companySymbols = $this->fileService->getCompanySymbols();
        $symbols = array_keys($companySymbols);

        return array_combine($symbols, $symbols);
    }
}
