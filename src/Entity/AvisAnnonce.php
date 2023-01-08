<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AvisAnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisAnnonceRepository::class)]
#[ApiResource]
class AvisAnnonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2000)]
    private ?string $message = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    #[ORM\ManyToOne(inversedBy: 'avisAnnonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $idAnnonce = null;

    #[ORM\Column(nullable: true)]
    private ?int $rating = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getIdAnnonce(): ?Annonce
    {
        return $this->idAnnonce;
    }

    public function setIdAnnonce(?Annonce $idAnnonce): self
    {
        $this->idAnnonce = $idAnnonce;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
