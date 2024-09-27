<?php

namespace App\Entity\Admin;

use App\Repository\Admin\CompoAssociationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompoAssociationRepository::class)]
class CompoAssociation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'compoAssociations')]
    private ?Member $refAdherent = null;

    #[ORM\ManyToOne(inversedBy: 'compoAssociations')]
    private ?RoleAssociation $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefAdherent(): ?Member
    {
        return $this->refAdherent;
    }

    public function setRefAdherent(?Member $refAdherent): static
    {
        $this->refAdherent = $refAdherent;

        return $this;
    }

    public function getRole(): ?RoleAssociation
    {
        return $this->role;
    }

    public function setRole(?RoleAssociation $role): static
    {
        $this->role = $role;

        return $this;
    }
}
