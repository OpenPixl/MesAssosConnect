<?php

namespace App\Entity\Admin;

use App\Repository\Admin\RoleAssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleAssociationRepository::class)]
class RoleAssociation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateAt = null;

    /**
     * @var Collection<int, CompoAssociation>
     */
    #[ORM\OneToMany(targetEntity: CompoAssociation::class, mappedBy: 'role')]
    private Collection $compoAssociations;

    public function __construct()
    {
        $this->compoAssociations = new ArrayCollection();
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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): static
    {
        $this->updateAt = $updateAt;

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
            $compoAssociation->setRole($this);
        }

        return $this;
    }

    public function removeCompoAssociation(CompoAssociation $compoAssociation): static
    {
        if ($this->compoAssociations->removeElement($compoAssociation)) {
            // set the owning side to null (unless already changed)
            if ($compoAssociation->getRole() === $this) {
                $compoAssociation->setRole(null);
            }
        }

        return $this;
    }
}
