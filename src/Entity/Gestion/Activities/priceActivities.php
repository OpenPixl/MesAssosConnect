<?php

namespace App\Entity\Gestion\Activities;

use App\Entity\Gestion\Associations\Association;
use App\Entity\Gestion\Associations\CampaignAdhesion;
use App\Repository\Gestion\Activities\priceActivitiesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: priceActivitiesRepository::class)]
class priceActivities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'priceActivities')]
    private ?Activity $activity = null;

    #[ORM\ManyToOne(inversedBy: 'priceActivities')]
    private ?CampaignAdhesion $campaign = null;

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

    public function getCampaign(): ?CampaignAdhesion
    {
        return $this->campaign;
    }

    public function setCampaign(?CampaignAdhesion $campaign): static
    {
        $this->campaign = $campaign;

        return $this;
    }
}
