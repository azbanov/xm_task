<?php

declare(strict_types=1);

namespace XM\Tests\Application\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class DashboardControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->request(Request::METHOD_GET, '/');
    }

    /**
     * @test
     */
    public function quotesHappyPathGeneratesForm()
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h6', 'Filter form');
    }

    /**
     * @test
     */
    public function quotesSubmitEmptyFormGenerateErrors()
    {
        $this->client->submitForm('quote_filter_form_submit');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('ul');
        $this->assertSelectorTextContains('li', 'This value should not be null.');
    }

    /**
     * @test
     */
    public function quotesSubmitEmptyEmailGenerateError()
    {
        $this->client->submitForm('quote_filter_form_submit', [
            'quote_filter_form[symbol]' => 'GOOG',
            'quote_filter_form[email]' => null,
            'quote_filter_form[start_date]' => '2023-04-07',
            'quote_filter_form[end_date]' => '2023-04-07',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('ul');
        $this->assertSelectorTextContains('li', 'This value should not be null.');
    }

    /**
     * @test
     */
    public function quotesSubmitEmptyStartDateGenerateError()
    {
        $this->client->submitForm('quote_filter_form_submit', [
            'quote_filter_form[symbol]' => 'GOOG',
            'quote_filter_form[email]' => 'test@test.com',
            'quote_filter_form[start_date]' => null,
            'quote_filter_form[end_date]' => '2023-04-07',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('ul');
        $this->assertSelectorTextContains('li', 'This value should not be null.');
    }

    /**
     * @test
     */
    public function quotesSubmitEmptyEndDateGenerateError()
    {
        $this->client->submitForm('quote_filter_form_submit', [
            'quote_filter_form[symbol]' => 'GOOG',
            'quote_filter_form[email]' => 'test@test.com',
            'quote_filter_form[start_date]' => '2023-04-07',
            'quote_filter_form[end_date]' => null,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('ul');
        $this->assertSelectorTextContains('li', 'This value should not be null.');
    }

    /**
     * @test
     */
    public function quotesSubmitStartDateMoreThanCurrentDateGenerateError()
    {
        $interval = \DateInterval::createFromDateString('1 day');
        $startDate = new \DateTime();
        $startDate->add($interval);

        $this->client->submitForm('quote_filter_form_submit', [
            'quote_filter_form[symbol]' => 'GOOG',
            'quote_filter_form[email]' => 'test@test.com',
            'quote_filter_form[start_date]' => $startDate->format('Y-m-d'),
            'quote_filter_form[end_date]' => '2023-04-07',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('ul');
        $this->assertSelectorTextContains('li', 'Start Date should be equal or less than current date.');
    }

    /**
     * @test
     */
    public function quotesSubmitStartDateMoreThanEndDateGenerateError()
    {
        $this->client->submitForm('quote_filter_form_submit', [
            'quote_filter_form[symbol]' => 'GOOG',
            'quote_filter_form[email]' => 'test@test.com',
            'quote_filter_form[start_date]' => '2023-04-07',
            'quote_filter_form[end_date]' => '2023-03-07',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('ul');
        $this->assertSelectorTextContains('li', 'Start Date should be equal or less than End Date date.');
    }

    /**
     * @test
     */
    public function quotesSubmitEndDateMoreThanCurrentDateGenerateError()
    {
        $interval = \DateInterval::createFromDateString('1 day');
        $endDate = new \DateTime();
        $endDate->add($interval);

        $this->client->submitForm('quote_filter_form_submit', [
            'quote_filter_form[symbol]' => 'GOOG',
            'quote_filter_form[email]' => 'test@test.com',
            'quote_filter_form[start_date]' => '2023-04-07',
            'quote_filter_form[end_date]' => $endDate->format('Y-m-d'),
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('ul');
        $this->assertSelectorTextContains('li', 'End Date should be equal or less than current date.');
    }

    /**
     * @test
     */
    public function quotesSubmitWrongEmailFormatGenerateError()
    {
        $this->client->submitForm('quote_filter_form_submit', [
            'quote_filter_form[symbol]' => 'GOOG',
            'quote_filter_form[email]' => 'test@',
            'quote_filter_form[start_date]' => '2023-04-07',
            'quote_filter_form[end_date]' => '2023-03-07',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('ul');
        $this->assertSelectorTextContains('li', 'This value is not a valid email address.');
    }
}
