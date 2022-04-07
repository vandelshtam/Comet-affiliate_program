<?php

namespace App\Entity;

use App\Repository\ReferralToEmailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReferralToEmailRepository::class)]
class ReferralToEmail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $user_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $referral_link;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $email_to_client;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReferralLink(): ?string
    {
        return $this->referral_link;
    }

    public function setReferralLink(?string $referral_link): self
    {
        $this->referral_link = $referral_link;

        return $this;
    }

    public function getEmailToClient(): ?string
    {
        return $this->email_to_client;
    }

    public function setEmailToClient(?string $email_to_client): self
    {
        $this->email_to_client = $email_to_client;

        return $this;
    }
}
