<?php

declare(strict_types=1);

namespace App\Entity\Air;

use App\Repository\Air\AirportsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AirportsRepository::class)]
class Airports
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 10)]
    private string $codeIata;

    #[ORM\Column(length: 10)]
    private string $codeIcao;

    #[ORM\Column(length: 2)]
    private string $country;

    #[ORM\Column(length: 255)]
    private string $municipality;

    #[ORM\Column(length: 255)]
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getMunicipality(): string
    {
        return $this->municipality;
    }

    public function setMunicipality(string $municipality): self
    {
        $this->municipality = $municipality;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
