<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DocumentProprietaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentProprietaireRepository::class)]
#[ApiResource]
class DocumentProprietaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ORM\JoinColumn(nullable: false)]
    private ?string $lien = null;

    #[ORM\Column(length: 255)]
    #[ORM\JoinColumn(nullable: false)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'documentProprietaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

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

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }



    // private $brochureFilename;

    // public function getBrochureFilename()
    // {
    //     return $this->brochureFilename;
    // }

    // public function setBrochureFilename($brochureFilename)
    // {
    //     $this->brochureFilename = $brochureFilename;
    //     return $this;
    // }
}
