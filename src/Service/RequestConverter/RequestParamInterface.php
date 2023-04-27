<?php

declare(strict_types=1);

namespace App\Service\RequestConverter;

/**
 * Интерфейс объектов значений, передаваемых через запрос.
 */
interface RequestParamInterface
{
    /**
     * Преобразовать значения объекта в массив.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
