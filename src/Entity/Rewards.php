<?php

namespace App\Entity;

use App\Repository\RewardsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RewardsRepository::class)]
class Rewards
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $rewards_usdt;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $rewards_token;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $user_id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $personal_data_id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $referral_network_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRewardsUsdt(): ?int
    {
        return $this->rewards_usdt;
    }

    public function setRewardsUsdt(?int $rewards_usdt): self
    {
        $this->rewards_usdt = $rewards_usdt;

        return $this;
    }

    public function getRewardsToken(): ?int
    {
        return $this->rewards_token;
    }

    public function setRewardsToken(?int $rewards_token): self
    {
        $this->rewards_token = $rewards_token;

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

    public function getPersonalDataId(): ?int
    {
        return $this->personal_data_id;
    }

    public function setPersonalDataId(?int $personal_data_id): self
    {
        $this->personal_data_id = $personal_data_id;

        return $this;
    }

    public function getReferralNetworkId(): ?int
    {
        return $this->referral_network_id;
    }

    public function setReferralNetworkId(?int $referral_network_id): self
    {
        $this->referral_network_id = $referral_network_id;

        return $this;
    }
}
