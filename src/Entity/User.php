<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'registration_form.notification_email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $personal_data_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $username;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $role;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $referral_link;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Wallet::class, cascade: ['persist', 'remove'])]
    private $wallet;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $pesonal_code;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $pakage_status;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $pakage_id;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_at;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $multi_pakage;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $secret_code;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Pakege::class)]
    private $pakeges;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $locale;




    public function __construct()
    {
        $this->pakages = new ArrayCollection();
        $this->pakeges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPersonalDataId(): ?int
    {
        return $this->personal_data_id;
    }

    public function setPersonalDataId(int $personal_data_id): self
    {
        $this->personal_data_id = $personal_data_id;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

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

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(Wallet $wallet): self
    {
        // set the owning side of the relation if necessary
        if ($wallet->getUser() !== $this) {
            $wallet->setUser($this);
        }

        $this->wallet = $wallet;

        return $this;
    }

    public function __toString()
    {
      return $this->getId();
    }

    public function getPesonalCode(): ?string
    {
        return $this->pesonal_code;
    }

    public function setPesonalCode(?string $pesonal_code): self
    {
        $this->pesonal_code = $pesonal_code;

        return $this;
    }

    public function getPakageStatus(): ?int
    {
        return $this->pakage_status;
    }

    public function setPakageStatus(?int $pakage_status): self
    {
        $this->pakage_status = $pakage_status;

        return $this;
    }


    public function getPakageId(): ?int
    {
        return $this->pakage_id;
    }

    public function setPakageId(?int $pakage_id): self
    {
        $this->pakage_id = $pakage_id;

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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getMultiPakage(): ?int
    {
        return $this->multi_pakage;
    }

    public function setMultiPakage(?int $multi_pakage): self
    {
        $this->multi_pakage = $multi_pakage;

        return $this;
    }

    public function getSecretCode(): ?string
    {
        return $this->secret_code;
    }

    public function setSecretCode(?string $secret_code): self
    {
        $this->secret_code = $secret_code;

        return $this;
    }

    /**
     * @return Collection<int, Pakege>
     */
    public function getPakeges(): Collection
    {
        return $this->pakeges;
    }

    public function addPakege(Pakege $pakege): self
    {
        if (!$this->pakeges->contains($pakege)) {
            $this->pakeges[] = $pakege;
            $pakege->setUser($this);
        }

        return $this;
    }

    public function removePakege(Pakege $pakege): self
    {
        if ($this->pakeges->removeElement($pakege)) {
            // set the owning side to null (unless already changed)
            if ($pakege->getUser() === $this) {
                $pakege->setUser(null);
            }
        }

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

}
