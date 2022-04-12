<?php

namespace App\Entity;

use App\Repository\PkegeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PkegeRepository::class)]
class Pkege
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $activation;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $token;

    #[ORM\OneToOne(inversedBy: 'pkege', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivation(): ?int
    {
        return $this->activation;
    }

    public function setActivation(?int $activation): self
    {
        $this->activation = $activation;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
