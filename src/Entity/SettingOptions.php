<?php

namespace App\Entity;

use App\Repository\SettingOptionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingOptionsRepository::class)]
class SettingOptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $payments_singleline;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $payments_direct;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $cash_back;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $all_price_pakage;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $accrual_limit;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $system_revenues;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $start_day;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $fast_start;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $update_day;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $limit_wallet_from_line;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentsSingleline(): ?int
    {
        return $this->payments_singleline;
    }

    public function setPaymentsSingleline(?int $payments_singleline): self
    {
        $this->payments_singleline = $payments_singleline;

        return $this;
    }

    public function getPaymentsDirect(): ?int
    {
        return $this->payments_direct;
    }

    public function setPaymentsDirect(?int $payments_direct): self
    {
        $this->payments_direct = $payments_direct;

        return $this;
    }

    public function getCashBack(): ?int
    {
        return $this->cash_back;
    }

    public function setCashBack(?int $cash_back): self
    {
        $this->cash_back = $cash_back;

        return $this;
    }

    public function getAllPricePakage(): ?int
    {
        return $this->all_price_pakage;
    }

    public function setAllPricePakage(?int $all_price_pakage): self
    {
        $this->all_price_pakage = $all_price_pakage;

        return $this;
    }

    public function getAccrualLimit(): ?int
    {
        return $this->accrual_limit;
    }

    public function setAccrualLimit(?int $accrual_limit): self
    {
        $this->accrual_limit = $accrual_limit;

        return $this;
    }

    public function getSystemRevenues(): ?int
    {
        return $this->system_revenues;
    }

    public function setSystemRevenues(?int $system_revenues): self
    {
        $this->system_revenues = $system_revenues;

        return $this;
    }

    public function getStartDay(): ?int
    {
        return $this->start_day;
    }

    public function setStartDay(?int $start_day): self
    {
        $this->start_day = $start_day;

        return $this;
    }

    public function getFastStart(): ?int
    {
        return $this->fast_start;
    }

    public function setFastStart(?int $fast_start): self
    {
        $this->fast_start = $fast_start;

        return $this;
    }

    public function getUpdateDay(): ?int
    {
        return $this->update_day;
    }

    public function setUpdateDay(?int $update_day): self
    {
        $this->update_day = $update_day;

        return $this;
    }

    public function getLimitWalletFromLine(): ?int
    {
        return $this->limit_wallet_from_line;
    }

    public function setLimitWalletFromLine(?int $limit_wallet_from_line): self
    {
        $this->limit_wallet_from_line = $limit_wallet_from_line;

        return $this;
    }
}
