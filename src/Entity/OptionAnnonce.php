<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\OptionAnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionAnnonceRepository::class)]
#[ApiResource]
class OptionAnnonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2000)]
    private ?string $valeur = null;

    #[ORM\ManyToOne(inversedBy: 'idAnnonce')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $idAnnonce = null;

    #[ORM\ManyToOne(inversedBy: 'optionAnnonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LebelleOptionAnnonce $idLibelle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getIdLibelle(): ?LebelleOptionAnnonce
    {
        return $this->idLibelle;
    }

    public function setIdLibelle(?LebelleOptionAnnonce $idLibelle): self
    {
        $this->idLibelle = $idLibelle;

        return $this;
    }

    public function setIdLibelle_id($idLibelle)
    {
        $this->idLibelle = $idLibelle;

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

}
