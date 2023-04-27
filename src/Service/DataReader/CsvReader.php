<?php

declare(strict_types=1);

namespace App\Service\DataReader;

use League\Csv\Reader;

class CsvReader implements ReaderInterface
{
    private Reader $csv;

    /** {@inheritDoc} */
    public function open(string $filename): void
    {
        // Откроем CSV файл
        $this->csv = Reader::createFromPath($filename, 'r');
        $this->csv->setDelimiter(',');
    }

    /** {@inheritDoc} */
    public function getHeader(): array
    {
        $this->csv->setHeaderOffset(0); // Первая строка содержит названия полей

        return $this->csv->getHeader(); // Получим названия полей
    }

    /** {@inheritDoc} */
    public function getRecords(array $header): iterable
    {
        /** @var string[] $row */
        foreach ($this->csv->getRecords($header) as $row) {
            yield $row;
        }
    }
}
