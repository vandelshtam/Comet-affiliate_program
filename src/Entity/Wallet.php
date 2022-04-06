<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $usdt;

    #[ORM\Column(type: 'float', nullable: true)]
    private $etherium;

    #[ORM\Column(type: 'float', nullable: true)]
    private $bitcoin;

    #[ORM\Column(type: 'float', nullable: true)]
    private $cometpoin;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $user_id;

    #[ORM\OneToOne(inversedBy: 'wallet', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsdt(): ?int
    {
        return $this->usdt;
    }

    public function setUsdt(?int $usdt): self
    {
        $this->usdt = $usdt;

        return $this;
    }

    public function getEtherium(): ?int
    {
        return $this->etherium;
    }

    public function setEtherium(?int $etherium): self
    {
        $this->etherium = $etherium;

        return $this;
    }

    public function getBitcoin(): ?int
    {
        return $this->bitcoin;
    }

    public function setBitcoin(?int $bitcoin): self
    {
        $this->bitcoin = $bitcoin;

        return $this;
    }

    public function getCometpoin(): ?int
    {
        return $this->cometpoin;
    }

    public function setCometpoin(?int $cometpoin): self
    {
        $this->cometpoin = $cometpoin;

        return $this;
    }

    public function __toString()
    {
      return $this->getId();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser_id(): ?int
    {
        return $this->usdt;
    }
    

    
}
