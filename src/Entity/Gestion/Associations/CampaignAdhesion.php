<?php

namespace App\Entity\Gestion\Associations;

use App\Entity\Gestion\Activities\Registration;
use App\Entity\Gestion\Activities\priceActivities;
use App\Entity\Gestion\Adhesions\Adherent;
use App\Entity\Gestion\Adhesions\Adhesion;
use App\Repository\Gestion\Associations\CampaignAdhesionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampaignAdhesionRepository::class)]
class CampaignAdhesion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'campaignAdhesions')]
    private ?Association $Association = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $startAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $finishAt = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Adhesion>
     */
    #[ORM\OneToMany(targetEntity: Adhesion::class, mappedBy: 'campaign')]
    private Collection $adhesions;

    /**
     * @var Collection<int, priceActivities>
     */
    #[ORM\OneToMany(targetEntity: priceActivities::class, mappedBy: 'campaign')]
    private Collection $priceActivities;

    /**
     * @var Collection<int, Registration>
     */
    #[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'campaign')]
    private Collection $registrations;

    public function __construct()
    {
        $this->adhesions = new ArrayCollection();
        $this->priceActivities = new ArrayCollection();
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssociation(): ?Association
    {
        return $this->Association;
    }

    public function setAssociation(?Association $Association): static
    {
        $this->Association = $Association;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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
            $adhesion->setCampaign($this);
        }

        return $this;
    }

    public function removeAdhesion(Adhesion $adhesion): static
    {
        if ($this->adhesions->removeElement($adhesion)) {
            // set the owning side to null (unless already changed)
            if ($adhesion->getCampaign() === $this) {
                $adhesion->setCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, priceActivities>
     */
    public function getPriceActivities(): Collection
    {
        return $this->priceActivities;
    }

    public function addPriceActivity(priceActivities $priceActivity): static
    {
        if (!$this->priceActivities->contains($priceActivity)) {
            $this->priceActivities->add($priceActivity);
            $priceActivity->setCampaign($this);
        }

        return $this;
    }

    public function removePriceActivity(priceActivities $priceActivity): static
    {
        if ($this->priceActivities->removeElement($priceActivity)) {
            // set the owning side to null (unless already changed)
            if ($priceActivity->getCampaign() === $this) {
                $priceActivity->setCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): static
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations->add($registration);
            $registration->setCampaign($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): static
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getCampaign() === $this) {
                $registration->setCampaign(null);
            }
        }

        return $this;
    }
}
