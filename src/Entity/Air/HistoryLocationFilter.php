<?php

declare(strict_types=1);

namespace App\Entity\Air;

use App\Service\RequestConverter\RequestParamInterface;
use Symfony\Component\Validator\Constraints as Assert;

class HistoryLocationFilter implements RequestParamInterface
{
    /**
     * Бортовой номер воздушного судна.
     */
    #[Assert\NotNull(message: 'Необходимо указать бортовой номер воздушного судна')]
    private ?string $tail = null;

    /**
     * Начало периода.
     */
    private ?\DateTimeInterface $fromDate = null;

    /**
     * Конец периода.
     */
    private ?\DateTimeInterface $toDate = null;

    /**
     * Формат вывода данных: json или xml.
     * По умолчанию - json.
     */
    #[Assert\NotNull(message: 'Необходимо указать формат вывода данных')]
    #[Assert\AtLeastOneOf([
        new Assert\EqualTo('json'),
        new Assert\EqualTo('xml'),
    ], message: 'Необходимо указать формат вывода данных: json или xml')]
    private string $format = 'json';

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'tail' => $this->tail,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'format' => $this->format,
        ];
    }

    public function getTail(): ?string
    {
        return $this->tail;
    }

    public function setTail(?string $tail): self
    {
        $this->tail = $tail;

        return $this;
    }

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(?\DateTimeInterface $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?\DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(?\DateTimeInterface $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }
}
