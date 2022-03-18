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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pakeges')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $token;

    #[ORM\Column(type: 'string', nullable: true)]
    private $activation;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $unique_code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $referral_link;

    #[ORM\OneToMany(mappedBy: 'pakege', targetEntity: ListReferralNetworks::class)]
    private $listReferralNetworks;

    

    public function __construct()
    {
        $this->referralNetworks = new ArrayCollection();
        $this->listReferralNetworks = new ArrayCollection();
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

    
    public function __toString()
    {
      return $this->getId();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getToken(): ?int
    {
        return $this->token;
    }

    public function setToken(?int $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getActivation(): ?string
    {
        return $this->activation;
    }

    public function setActivation(?string $activation): self
    {
        $this->activation = $activation;

        return $this;
    }

    public function getUniqueCode(): ?string
    {
        return $this->unique_code;
    }

    public function setUniqueCode(?string $unique_code): self
    {
        $this->unique_code = $unique_code;

        return $this;
    }

    public function getReferralLink(): ?string
    {
        return $this->referral_link;
    }

    public function setReferralLink(?string $referral_link): self
    {
        $this->referral_link = $referral_link;

        return $this;
    }

    /**
     * @return Collection<int, ListReferralNetworks>
     */
    public function getListReferralNetworks(): Collection
    {
        return $this->listReferralNetworks;
    }

    public function addListReferralNetwork(ListReferralNetworks $listReferralNetwork): self
    {
        if (!$this->listReferralNetworks->contains($listReferralNetwork)) {
            $this->listReferralNetworks[] = $listReferralNetwork;
            $listReferralNetwork->setPakege($this);
        }

        return $this;
    }

    public function removeListReferralNetwork(ListReferralNetworks $listReferralNetwork): self
    {
        if ($this->listReferralNetworks->removeElement($listReferralNetwork)) {
            // set the owning side to null (unless already changed)
            if ($listReferralNetwork->getPakege() === $this) {
                $listReferralNetwork->setPakege(null);
            }
        }

        return $this;
    }

    
}
