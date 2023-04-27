<?php

declare(strict_types=1);

namespace App\Service\DataReader;

interface ReaderInterface
{
    /**
     * Открыть набор данных по заданому пути и имени файла.
     */
    public function open(string $filename): void;

    /**
     * Поулчить массив заголовком столбцов.
     *
     * @return string[]
     */
    public function getHeader(): array;

    /**
     * Получить массив записей.
     *
     * @param string[] $header Массив названий столбцов
     *
     * @return string[][]
     */
    public function getRecords(array $header): iterable;
}
