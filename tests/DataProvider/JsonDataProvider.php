<?php

declare(strict_types=1);

namespace XM\Tests\DataProvider;

class JsonDataProvider
{
    /**
     * @throws \JsonException
     */
    public static function getJsonCompanies(): mixed
    {
        return json_decode(
            file_get_contents(__DIR__.'/json/companies.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * @throws \JsonException
     */
    public static function getJsonQuotes($associative = true): mixed
    {
        return json_decode(
            file_get_contents(__DIR__.'/json/quotes.json'),
            $associative,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    public static function getJsonCompanySymbols($associative = true): mixed
    {
        return json_decode(
            file_get_contents(__DIR__.'/json/companySymbols.json'),
            $associative,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}
