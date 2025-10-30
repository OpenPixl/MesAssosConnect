<?php

namespace App\Entity\Gestion\Activities;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Admin\Member;
use App\Entity\Gestion\Associations\Association;
use App\Repository\Gestion\Activities\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Association $asso = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, priceActivities>
     */
    #[ORM\OneToMany(targetEntity: priceActivities::class, mappedBy: 'activity')]
    private Collection $priceActivities;

    /**
     * @var Collection<int, Member>
     */
    #[ORM\ManyToMany(targetEntity: Member::class, inversedBy: 'activities')]
    private Collection $animateurs;

    /**
     * @var Collection<int, Registration>
     */
    #[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'activity')]
    private Collection $registrations;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->priceActivities = new ArrayCollection();
        $this->animateurs = new ArrayCollection();
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getasso(): ?Association
    {
        return $this->asso;
    }

    public function setasso(?Association $asso): static
    {
        $this->asso = $asso;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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
            $priceActivity->setActivity($this);
        }

        return $this;
    }

    public function removePriceActivity(priceActivities $priceActivity): static
    {
        if ($this->priceActivities->removeElement($priceActivity)) {
            // set the owning side to null (unless already changed)
            if ($priceActivity->getActivity() === $this) {
                $priceActivity->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getAnimateurs(): Collection
    {
        return $this->animateurs;
    }

    public function addAnimateur(Member $animateur): static
    {
        if (!$this->animateurs->contains($animateur)) {
            $this->animateurs->add($animateur);
        }

        return $this;
    }

    public function removeAnimateur(Member $animateur): static
    {
        $this->animateurs->removeElement($animateur);

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
            $registration->setActivity($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): static
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getActivity() === $this) {
                $registration->setActivity(null);
            }
        }

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
