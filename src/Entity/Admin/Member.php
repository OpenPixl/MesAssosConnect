<?php

namespace App\Entity\Admin;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Gestion\Adhesion;
use App\Repository\Admin\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ApiResource]
class Member implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $roleMember = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $civility = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $bisAddress = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 14)]
    private ?string $mobilePhone = null;

    #[ORM\Column(length: 14, nullable: true)]
    private ?string $homePhone = null;

    #[ORM\Column(length: 14, nullable: true)]
    private ?string $workPhone = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private $avatarName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $avatarSize = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private $avatarExt= null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateAt = null;

    /**
     * @var Collection<int, CompoAssociation>
     */
    #[ORM\OneToMany(targetEntity: CompoAssociation::class, mappedBy: 'refAdherent')]
    private Collection $compoAssociations;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthday = null;

    /**
     * @var Collection<int, Adhesion>
     */
    #[ORM\ManyToMany(targetEntity: Adhesion::class, mappedBy: 'members')]
    private Collection $adhesions;

    public function __construct()
    {
        $this->compoAssociations = new ArrayCollection();
        $this->adhesions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRoleMember(): ?string
    {
        return $this->roleMember;
    }

    public function setRoleMember(string $roleMember): static
    {
        $this->roleMember = $roleMember;

        return $this;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(?string $civility): static
    {
        $this->civility = $civility;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getBisAddress(): ?string
    {
        return $this->bisAddress;
    }

    public function setBisAddress(?string $bisAddress): static
    {
        $this->bisAddress = $bisAddress;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }
    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    public function setMobilePhone(string $mobilePhone): static
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    public function getHomePhone(): ?string
    {
        return $this->homePhone;
    }

    public function setHomePhone(?string $homePhone): static
    {
        $this->homePhone = $homePhone;

        return $this;
    }

    public function getWorkPhone(): ?string
    {
        return $this->workPhone;
    }

    public function setWorkPhone(?string $workPhone): static
    {
        $this->workPhone = $workPhone;

        return $this;
    }

    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    public function setAvatarName(?string $avatarName): static
    {
        $this->avatarName = $avatarName;

        return $this;
    }

    public function getAvatarSize(): ?string
    {
        return $this->avatarSize;
    }

    public function setAvatarSize(?string $avatarSize): static
    {
        $this->avatarSize = $avatarSize;

        return $this;
    }

    public function getAvatarExt(): ?string
    {
        return $this->avatarExt;
    }

    public function setAvatarExt(?string $avatarExt): static
    {
        $this->avatarExt = $avatarExt;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    #[ORM\PrePersist]
    public function setCreateAt(): self
    {
        $this->createAt = new \DateTime('now');

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdateAt(): self
    {
        $this->updateAt = new \DateTime('now');

        return $this;
    }

    /**
     * @return Collection<int, CompoAssociation>
     */
    public function getCompoAssociations(): Collection
    {
        return $this->compoAssociations;
    }

    public function addCompoAssociation(CompoAssociation $compoAssociation): static
    {
        if (!$this->compoAssociations->contains($compoAssociation)) {
            $this->compoAssociations->add($compoAssociation);
            $compoAssociation->setRefAdherent($this);
        }

        return $this;
    }

    public function removeCompoAssociation(CompoAssociation $compoAssociation): static
    {
        if ($this->compoAssociations->removeElement($compoAssociation)) {
            // set the owning side to null (unless already changed)
            if ($compoAssociation->getRefAdherent() === $this) {
                $compoAssociation->setRefAdherent(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function __toString()
    {
        return $this->civility." ".$this->firstName." ".$this->lastName;
    }

    /**
     * @return Collection<int, Adhesion>
     */
    public function getAdhesions(): Collection
    {
        return $this->adhesions;
    }

    public function addAdhesion(Adhesion $adhesion): static
    {
        if (!$this->adhesions->contains($adhesion)) {
            $this->adhesions->add($adhesion);
            $adhesion->addMember($this);
        }

        return $this;
    }

    public function removeAdhesion(Adhesion $adhesion): static
    {
        if ($this->adhesions->removeElement($adhesion)) {
            $adhesion->removeMember($this);
        }

        return $this;
    }
}
