<?php

namespace App\Entity\Gestion\Adhesions;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Gestion\Associations\CampaignAdhesion;
use App\Repository\Gestion\Adhesions\AdhesionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdhesionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Adhesion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'adhesions')]
    private ?CampaignAdhesion $campaign = null;

    #[ORM\ManyToOne(inversedBy: 'adhesions')]
    private ?Cotisation $cotisation = null;

    /**
     * @var Collection<int, Adherent>
     */
    #[ORM\ManyToMany(targetEntity: Adherent::class, inversedBy: 'adhesions')]
    private Collection $adherent;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $priceCotisation = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $payBy = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $payAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $startAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $finishAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->adherent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampaign(): ?CampaignAdhesion
    {
        return $this->campaign;
    }

    public function setCampaign(?CampaignAdhesion $campaign): static
    {
        $this->campaign = $campaign;

        return $this;
    }

    public function getCotisation(): ?Cotisation
    {
        return $this->cotisation;
    }

    public function setCotisation(?Cotisation $cotisation): static
    {
        $this->cotisation = $cotisation;

        return $this;
    }

    /**
     * @return Collection<int, Adherent>
     */
    public function getAdherent(): Collection
    {
        return $this->adherent;
    }

    public function addAdherent(Adherent $adherent): static
    {
        if (!$this->adherent->contains($adherent)) {
            $this->adherent->add($adherent);
        }

        return $this;
    }

    public function removeAdherent(Adherent $adherent): static
    {
        $this->adherent->removeElement($adherent);

        return $this;
    }

    public function getPriceCotisation(): ?string
    {
        return $this->priceCotisation;
    }

    public function setPriceCotisation(?string $priceCotisation): static
    {
        $this->priceCotisation = $priceCotisation;

        return $this;
    }

    public function getPayBy(): ?string
    {
        return $this->payBy;
    }

    public function setPayBy(?string $payBy): static
    {
        $this->payBy = $payBy;

        return $this;
    }

    public function getPayAt(): ?\DateTime
    {
        return $this->payAt;
    }

    public function setPayAt(?\DateTime $payAt): static
    {
        $this->payAt = $payAt;

        return $this;
    }

    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTime $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getFinishAt(): ?\DateTime
    {
        return $this->finishAt;
    }

    public function setFinishAt(\DateTime $finishAt): static
    {
        $this->finishAt = $finishAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

}
