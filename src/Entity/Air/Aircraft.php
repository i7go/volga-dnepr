<?php

declare(strict_types=1);

namespace App\Entity\Air;

use App\Repository\Air\AircraftRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AircraftRepository::class)]
#[ORM\Index(columns: ['tail'])]
class Aircraft
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    private string $tail;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTail(): string
    {
        return $this->tail;
    }

    public function setTail(string $tail): self
    {
        $this->tail = $tail;

        return $this;
    }
}
