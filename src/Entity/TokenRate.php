<?php

namespace App\Entity;

use App\Repository\TokenRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenRateRepository::class)]
class TokenRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $exchange_rate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExchangeRate(): ?int
    {
        return $this->exchange_rate;
    }

    public function setExchangeRate(int $exchange_rate): self
    {
        $this->exchange_rate = $exchange_rate;

        return $this;
    }
}
