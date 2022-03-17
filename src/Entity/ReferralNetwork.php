<?php

namespace App\Entity;

use App\Repository\ReferralNetworkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReferralNetworkRepository::class)]
class ReferralNetwork
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $user_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $user_status;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $personal_data_id;

    #[ORM\ManyToOne(targetEntity: Pakege::class, inversedBy: 'referralNetworks')]
    #[ORM\JoinColumn(nullable: false)]
    private $pakege;

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

    public function getUserStatus(): ?string
    {
        return $this->user_status;
    }

    public function setUserStatus(?string $user_status): self
    {
        $this->user_status = $user_status;

        return $this;
    }

    public function getPersonalDataId(): ?int
    {
        return $this->personal_data_id;
    }

    public function setPersonalDataId(?int $personal_data_id): self
    {
        $this->personal_data_id = $personal_data_id;

        return $this;
    }

    public function getPakege(): ?Pakege
    {
        return $this->pakege;
    }

    public function setPakege(?Pakege $pakege): self
    {
        $this->pakege = $pakege;

        return $this;
    }
    public function __toString()
    {
      return $this->getPakege();
    }
}
