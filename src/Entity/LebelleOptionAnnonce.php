<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LebelleOptionAnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LebelleOptionAnnonceRepository::class)]
#[ApiResource]
class LebelleOptionAnnonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne]
    private ?category $idCompany = null;


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString()
    {
        return $this->id;
    }
    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIdCompany(): ?category
    {
        return $this->idCompany;
    }

    public function setIdCompany(?category $idCompany): self
    {
        $this->idCompany = $idCompany;

        return $this;
    }

}