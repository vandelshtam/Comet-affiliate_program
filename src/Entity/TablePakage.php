<?php

namespace App\Entity;

use App\Repository\TablePakageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TablePakageRepository::class)]
class TablePakage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $price_pakage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPricePakage(): ?string
    {
        return $this->price_pakage;
    }

    public function setPricePakage(?string $price_pakage): self
    {
        $this->price_pakage = $price_pakage;

        return $this;
    }
}
