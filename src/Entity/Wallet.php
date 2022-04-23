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

    #[ORM\Column(type: 'float', nullable: true)]
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

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsdt(): ?float
    {
        return $this->usdt;
    }

    public function setUsdt(?float $usdt): self
    {
        $this->usdt = $usdt;

        return $this;
    }

    public function getEtherium(): ?float
    {
        return $this->etherium;
    }

    public function setEtherium(?float $etherium): self
    {
        $this->etherium = $etherium;

        return $this;
    }

    public function getBitcoin(): ?float
    {
        return $this->bitcoin;
    }

    public function setBitcoin(?float $bitcoin): self
    {
        $this->bitcoin = $bitcoin;

        return $this;
    }

    public function getCometpoin(): ?float
    {
        return $this->cometpoin;
    }

    public function setCometpoin(?float $cometpoin): self
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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
    

    
}
