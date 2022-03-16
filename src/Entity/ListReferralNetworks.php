<?php

namespace App\Entity;

use App\Repository\ListReferralNetworksRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListReferralNetworksRepository::class)]
class ListReferralNetworks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $referral_networks_id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $owner_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $owner_name;

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

    public function getReferralNetworksId(): ?int
    {
        return $this->referral_networks_id;
    }

    public function setReferralNetworksId(?int $referral_networks_id): self
    {
        $this->referral_networks_id = $referral_networks_id;

        return $this;
    }

    public function getOwnerId(): ?int
    {
        return $this->owner_id;
    }

    public function setOwnerId(?int $owner_id): self
    {
        $this->owner_id = $owner_id;

        return $this;
    }

    public function getOwnerName(): ?string
    {
        return $this->owner_name;
    }

    public function setOwnerName(?string $owner_name): self
    {
        $this->owner_name = $owner_name;

        return $this;
    }
}
