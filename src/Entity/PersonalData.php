<?php

namespace App\Entity;

use App\Repository\PersonalDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonalDataRepository::class)]
class PersonalData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $surname;

    #[ORM\Column(type: 'integer')]
    private $phone;

    #[ORM\Column(type: 'string', length: 255)]
    private $state;

    #[ORM\Column(type: 'string', length: 255)]
    private $region;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    private $street;

    #[ORM\Column(type: 'string', length: 255)]
    private $house;

    #[ORM\Column(type: 'integer')]
    private $frame;

    #[ORM\Column(type: 'integer')]
    private $apartment;

    // /**
    //  * @ORM\Column(type="integer", length=250, nullable=true)
    //  */
    #[ORM\Column(type: 'integer', nullable: true )]
    private $user_id;

    #[ORM\Column(type: 'integer')]
    private $indexcity;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $users_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getHouse(): ?string
    {
        return $this->house;
    }

    public function setHouse(string $house): self
    {
        $this->house = $house;

        return $this;
    }

    public function getFrame(): ?int
    {
        return $this->frame;
    }

    public function setFrame(int $frame): self
    {
        $this->frame = $frame;

        return $this;
    }

    public function getApartment(): ?int
    {
        return $this->apartment;
    }

    public function setApartment(int $apartment): self
    {
        $this->apartment = $apartment;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getIndexcity(): ?int
    {
        return $this->indexcity;
    }

    public function setIndexcity(int $indexcity): self
    {
        $this->indexcity = $indexcity;

        return $this;
    }

    public function getUsersId(): ?int
    {
        return $this->users_id;
    }

    public function setUsersId(?int $users_id): self
    {
        $this->users_id = $users_id;

        return $this;
    }
}
