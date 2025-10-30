<?php

namespace App\Entity\Gestion\Activities;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Gestion\Associations\Association;
use App\Entity\Gestion\Associations\CampaignAdhesion;
use App\Repository\Gestion\Activities\RegistrationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Registration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    private ?Activity $activity = null;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    private ?Association $member = null;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    private ?CampaignAdhesion $campaign = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getMember(): ?Association
    {
        return $this->member;
    }

    public function setMember(?Association $member): static
    {
        $this->member = $member;

        return $this;
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
