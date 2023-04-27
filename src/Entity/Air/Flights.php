<?php

declare(strict_types=1);

namespace App\Entity\Air;

use App\Repository\Air\FlightsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FlightsRepository::class)]
class Flights
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Aircraft::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Serializer\Ignore]
    private Aircraft $aircraft;

    #[ORM\ManyToOne(targetEntity: Airports::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Serializer\Ignore]
    private Airports $airport1;

    #[ORM\ManyToOne(targetEntity: Airports::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Serializer\Ignore]
    private Airports $airport2;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private \DateTimeInterface $takeoff;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private \DateTimeInterface $landing;

    #[ORM\Column(name: 'aload')]
    #[Assert\NotNull(groups: ['apiView'])]
    private int $load;

    #[ORM\Column]
    #[Assert\NotNull(groups: ['apiView'])]
    private int $offload;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getLanding(): \DateTimeInterface
    {
        return $this->landing;
    }

    public function setLanding(\DateTimeInterface $landing): self
    {
        $this->landing = $landing;

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

    public function getOffload(): int
    {
        return $this->offload;
    }

    public function setOffload(int $offload): self
    {
        $this->offload = $offload;

        return $this;
    }

    public function getAircraft(): Aircraft
    {
        return $this->aircraft;
    }

    public function setAircraft(Aircraft $aircraft): self
    {
        $this->aircraft = $aircraft;

        return $this;
    }

    public function getAirport1(): Airports
    {
        return $this->airport1;
    }

    public function setAirport1(Airports $airport1): self
    {
        $this->airport1 = $airport1;

        return $this;
    }

    public function getAirport2(): Airports
    {
        return $this->airport2;
    }

    public function setAirport2(Airports $airport2): self
    {
        $this->airport2 = $airport2;

        return $this;
    }
}
