<?php

namespace App\Entity\Gestion\Associations;

use App\Repository\Gestion\Associations\EquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $typeEquipment = null;

    #[ORM\ManyToOne(inversedBy: 'equipment')]
    private ?Association $asso = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTypeEquipment(): ?string
    {
        return $this->typeEquipment;
    }

    public function setTypeEquipment(string $typeEquipment): static
    {
        $this->typeEquipment = $typeEquipment;

        return $this;
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
}
