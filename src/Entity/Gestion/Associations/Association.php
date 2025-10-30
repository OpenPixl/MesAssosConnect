<?php

namespace App\Entity\Gestion\Associations;

use App\Entity\Gestion\Activities\Activity;
use App\Entity\Gestion\Activities\Registration;
use App\Entity\Gestion\Activities\categoryActivities;
use App\Entity\Gestion\Activities\priceActivities;
use App\Entity\Gestion\Adhesions\Adherent;
use App\Entity\Gestion\Adhesions\Cotisation;
use App\Repository\Gestion\Associations\AssociationRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $object = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isRna = false;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $numRna = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $bisAddress = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 14, nullable: true)]
    private ?string $contactPhone = null;

    #[ORM\Column(length: 50)]
    private ?string $contactEmail = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $site = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $linkFb = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $linkInst = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $linkGoo = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private $logoName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $logoSize = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private $logoExt= null;

    /**
     * @var Collection<int, Adherent>
     */
    #[ORM\OneToMany(targetEntity: Adherent::class, mappedBy: 'Association')]
    private Collection $adherents;

    /**
     * @var Collection<int, Cotisation>
     */
    #[ORM\OneToMany(targetEntity: Cotisation::class, mappedBy: 'association')]
    private Collection $cotisations;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $seasonStart = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $seasonEnd = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateAt = null;

    /**
     * @var Collection<int, CampaignAdhesion>
     */
    #[ORM\OneToMany(targetEntity: CampaignAdhesion::class, mappedBy: 'Association')]
    private Collection $campaignAdhesions;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'Assocotion')]
    private Collection $activities;

    /**
     * @var Collection<int, categoryActivities>
     */
    #[ORM\OneToMany(targetEntity: categoryActivities::class, mappedBy: 'association')]
    private Collection $categoryActivities;

    /**
     * @var Collection<int, Registration>
     */
    #[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'member')]
    private Collection $registrations;

    /**
     * @var Collection<int, Equipment>
     */
    #[ORM\OneToMany(targetEntity: Equipment::class, mappedBy: 'asso')]
    private Collection $equipment;

    public function __construct()
    {
        $this->cotisations = new ArrayCollection();
        $this->campaignAdhesions = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->categoryActivities = new ArrayCollection();
        $this->registrations = new ArrayCollection();
        $this->equipment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * Permet d'initialiser le slug !
     * Utilisation de slugify pour transformer une chaine de caractères en slug
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeSlug() {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->name);
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(?string $Object): static
    {
        $this->object = $Object;

        return $this;
    }

    public function isRna(): ?bool
    {
        return $this->isRna;
    }

    public function SetIsRna(bool $isRna): static
    {
        $this->isRna = $isRna;

        return $this;
    }

    public function getNumRna(): ?string
    {
        return $this->numRna;
    }

    public function setNumRna(?string $numRna): static
    {
        $this->numRna = $numRna;

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

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): static
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getLinkFb(): ?string
    {
        return $this->linkFb;
    }

    public function setLinkFb(?string $linkFb): static
    {
        $this->linkFb = $linkFb;

        return $this;
    }

    public function getLinkInst(): ?string
    {
        return $this->linkInst;
    }

    public function setLinkInst(?string $linkInst): static
    {
        $this->linkInst = $linkInst;

        return $this;
    }

    public function getLinkGoo(): ?string
    {
        return $this->linkGoo;
    }

    public function setLinkGoo(?string $linkGoo): static
    {
        $this->linkGoo = $linkGoo;

        return $this;
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoName(?string $logoName): static
    {
        $this->logoName = $logoName;

        return $this;
    }

    public function getLogoSize(): ?string
    {
        return $this->logoSize;
    }

    public function setLogoSize(?string $logoSize): static
    {
        $this->logoSize = $logoSize;

        return $this;
    }

    public function getLogoExt(): ?string
    {
        return $this->logoExt;
    }

    public function setLogoExt(?string $logoExt): static
    {
        $this->logoExt = $logoExt;

        return $this;
    }

    /**
     * @return Collection<int, Adherent>
     */
    public function getAdherents(): Collection
    {
        return $this->adherents;
    }

    public function addAdherent(Adherent $adherent): static
    {
        if (!$this->adherents->contains($adherent)) {
            $this->adherents->add($adherent);
            $adherent->setAssociation($this);
        }

        return $this;
    }

    public function removeAdherent(Adherent $adherent): static
    {
        if ($this->adherents->removeElement($adherent)) {
            // set the owning side to null (unless already changed)
            if ($adherent->getAssociation() === $this) {
                $adherent->setAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cotisation>
     */
    public function getCotisations(): Collection
    {
        return $this->cotisations;
    }

    public function addCotisation(Cotisation $cotisation): static
    {
        if (!$this->cotisations->contains($cotisation)) {
            $this->cotisations->add($cotisation);
            $cotisation->setAssociation($this);
        }

        return $this;
    }

    public function removeCotisation(Cotisation $cotisation): static
    {
        if ($this->cotisations->removeElement($cotisation)) {
            // set the owning side to null (unless already changed)
            if ($cotisation->getAssociation() === $this) {
                $cotisation->setAssociation(null);
            }
        }

        return $this;
    }

    public function getSeasonStart(): ?string
    {
        return $this->seasonStart;
    }

    public function setSeasonStart(?string $seasonStart): static
    {
        $this->seasonStart = $seasonStart;

        return $this;
    }

    public function getSeasonEnd(): ?string
    {
        return $this->seasonEnd;
    }

    public function setSeasonEnd(?string $seasonEnd): static
    {
        $this->seasonEnd = $seasonEnd;

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

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, CampaignAdhesion>
     */
    public function getCampaignAdhesions(): Collection
    {
        return $this->campaignAdhesions;
    }

    public function addCampaignAdhesion(CampaignAdhesion $campaignAdhesion): static
    {
        if (!$this->campaignAdhesions->contains($campaignAdhesion)) {
            $this->campaignAdhesions->add($campaignAdhesion);
            $campaignAdhesion->setAssociation($this);
        }

        return $this;
    }

    public function removeCampaignAdhesion(CampaignAdhesion $campaignAdhesion): static
    {
        if ($this->campaignAdhesions->removeElement($campaignAdhesion)) {
            // set the owning side to null (unless already changed)
            if ($campaignAdhesion->getAssociation() === $this) {
                $campaignAdhesion->setAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setAssocotion($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getAssocotion() === $this) {
                $activity->setAssocotion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, categoryActivities>
     */
    public function getCategoryActivities(): Collection
    {
        return $this->categoryActivities;
    }

    public function addCategoryActivity(categoryActivities $categoryActivity): static
    {
        if (!$this->categoryActivities->contains($categoryActivity)) {
            $this->categoryActivities->add($categoryActivity);
            $categoryActivity->setAssociation($this);
        }

        return $this;
    }

    public function removeCategoryActivity(categoryActivities $categoryActivity): static
    {
        if ($this->categoryActivities->removeElement($categoryActivity)) {
            // set the owning side to null (unless already changed)
            if ($categoryActivity->getAssociation() === $this) {
                $categoryActivity->setAssociation(null);
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
            $registration->setMember($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): static
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getMember() === $this) {
                $registration->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipment>
     */
    public function getEquipment(): Collection
    {
        return $this->equipment;
    }

    public function addEquipment(Equipment $equipment): static
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment->add($equipment);
            $equipment->setAsso($this);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): static
    {
        if ($this->equipment->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getAsso() === $this) {
                $equipment->setAsso(null);
            }
        }

        return $this;
    }
}
