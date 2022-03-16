<?php

namespace App\Entity;

use App\Repository\PakegeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PakegeRepository::class)]
class Pakege
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $user_id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $price;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $referral_networks_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $client_code;

    #[ORM\OneToMany(mappedBy: 'pakege', targetEntity: ReferralNetwork::class)]
    private $referralNetworks;

    public function __construct()
    {
        $this->referralNetworks = new ArrayCollection();
    }

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

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getReferralNetworksId(): ?int
    {
        return $this->referral_networks_id;
    }

    public function setReferralNetworksId(?int $referral_networks_id): self
    {
        $this->referral_networks_id = $referral_networks_id;

        return $this;
    }

    public function getClientCode(): ?string
    {
        return $this->client_code;
    }

    public function setClientCode(?string $client_code): self
    {
        $this->client_code = $client_code;

        return $this;
    }

    /**
     * @return Collection<int, ReferralNetwork>
     */
    public function getReferralNetworks(): Collection
    {
        return $this->referralNetworks;
    }

    public function addReferralNetwork(ReferralNetwork $referralNetwork): self
    {
        if (!$this->referralNetworks->contains($referralNetwork)) {
            $this->referralNetworks[] = $referralNetwork;
            $referralNetwork->setPakege($this);
        }

        return $this;
    }

    public function removeReferralNetwork(ReferralNetwork $referralNetwork): self
    {
        if ($this->referralNetworks->removeElement($referralNetwork)) {
            // set the owning side to null (unless already changed)
            if ($referralNetwork->getPakege() === $this) {
                $referralNetwork->setPakege(null);
            }
        }

        return $this;
    }
}
