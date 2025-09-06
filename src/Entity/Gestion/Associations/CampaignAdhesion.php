<?php

namespace App\Entity\Gestion\Associations;

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

    public function __construct()
    {
        $this->adhesions = new ArrayCollection();
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
}
