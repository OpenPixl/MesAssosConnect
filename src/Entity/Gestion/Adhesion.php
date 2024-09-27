<?php

namespace App\Entity\Gestion;

use App\Entity\Admin\Association;
use App\Entity\Admin\Member;
use App\Repository\Gestion\AdhesionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdhesionRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Adhesion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'adhesions')]
    private ?Association $asso = null;

    #[ORM\ManyToOne(inversedBy: 'adhesions')]
    private ?typeAdhesion $typeAdhesion = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $cotisation = null;

    #[ORM\Column]
    private ?bool $isPaid = false;

    #[ORM\Column]
    private ?bool $isFree = false;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $paidAt = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $paidBy = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $refPaid = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateAt = null;

    /**
     * @var Collection<int, Member>
     */
    #[ORM\ManyToMany(targetEntity: Member::class, inversedBy: 'adhesions')]
    private Collection $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAsso(): ?Association
    {
        return $this->asso;
    }

    public function setAsso(?Association $asso): static
    {
        $this->asso = $asso;

        return $this;
    }

    public function getTypeAdhesion(): ?typeAdhesion
    {
        return $this->typeAdhesion;
    }

    public function setTypeAdhesion(?typeAdhesion $typeAdhesion): static
    {
        $this->typeAdhesion = $typeAdhesion;

        return $this;
    }

    public function getCotisation(): ?string
    {
        return $this->cotisation;
    }

    public function setCotisation(?string $cotisation): static
    {
        $this->cotisation = $cotisation;

        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setISPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    public function isFree(): ?bool
    {
        return $this->isFree;
    }

    public function setIsFree(bool $isFree): static
    {
        $this->isFree = $isFree;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeInterface
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeInterface $paidAt): static
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getPaidBy(): ?string
    {
        return $this->paidBy;
    }

    public function setPaidBy(?string $paidBy): static
    {
        $this->paidBy = $paidBy;

        return $this;
    }

    public function getRefPaid(): ?string
    {
        return $this->refPaid;
    }

    public function setRefPaid(?string $refPaid): static
    {
        $this->refPaid = $refPaid;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    #[ORM\PrePersist]
    public function setCreateAt(): self
    {
        $this->createAt = new \DateTime();

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdateAt(): static
    {
        $this->updateAt = new \DateTime();

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(Member $member): static
    {
        $this->members->removeElement($member);

        return $this;
    }

    public function __toString()
    {
        return $this->typeAdhesion;
    }
}
