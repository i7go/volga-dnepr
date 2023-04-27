<?php

declare(strict_types=1);

namespace App\Entity\Air;

use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class HistoryLocation
{
    /**
     * Id аэропорта.
     */
    #[Serializer\Groups(['apiView'])]
    #[Assert\NotNull(groups: ['apiView'])]
    private int $airportId;

    /**
     * Код IATA аэропорта.
     */
    #[Serializer\Groups(['apiView'])]
    #[Assert\NotNull(groups: ['apiView'])]
    private string $codeIata;

    /**
     * Код ICAO аэропорта.
     */
    #[Serializer\Groups(['apiView'])]
    #[Assert\NotNull(groups: ['apiView'])]
    private string $codeIcao;

    /**
     * Время посадки в этот аэропорт
     */
    #[Serializer\Groups(['apiView'])]
    #[Assert\NotNull(groups: ['apiView'])]
    private \DateTimeInterface $landing;

    /**
     * Объём разгрузки.
     */
    #[Serializer\Groups(['apiView'])]
    #[Assert\NotNull(groups: ['apiView'])]
    private int $offload;

    /**
     * Объём загрузки.
     */
    #[Serializer\Groups(['apiView'])]
    #[Assert\NotNull(groups: ['apiView'])]
    private int $load;

    /**
     * Время вылета из этого аэропорта.
     */
    #[Serializer\Groups(['apiView'])]
    #[Assert\NotNull(groups: ['apiView'])]
    private \DateTimeInterface $takeoff;

    public function getAirportId(): int
    {
        return $this->airportId;
    }

    public function setAirportId(int $airportId): self
    {
        $this->airportId = $airportId;

        return $this;
    }

    public function getCodeIata(): string
    {
        return $this->codeIata;
    }

    public function setCodeIata(string $codeIata): self
    {
        $this->codeIata = $codeIata;

        return $this;
    }

    public function getCodeIcao(): string
    {
        return $this->codeIcao;
    }

    public function setCodeIcao(string $codeIcao): self
    {
        $this->codeIcao = $codeIcao;

        return $this;
    }

    public function getLanding(): \DateTimeInterface
    {
        return $this->landing;
    }

    public function setLanding(\DateTimeInterface $landing): self
    {
        $this->landing = $landing;

        return $this;
    }

    public function getOffload(): int
    {
        return $this->offload;
    }

    public function setOffload(int $offload): self
    {
        $this->offload = $offload;

        return $this;
    }

    public function getLoad(): int
    {
        return $this->load;
    }

    public function setLoad(int $load): self
    {
        $this->load = $load;

        return $this;
    }

    public function getTakeoff(): \DateTimeInterface
    {
        return $this->takeoff;
    }

    public function setTakeoff(\DateTimeInterface $takeoff): self
    {
        $this->takeoff = $takeoff;

        return $this;
    }
}
