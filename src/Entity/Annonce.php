<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
#[ApiResource]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $Ville = null;

    #[ORM\Column(length: 255)]
    private ?string $CodePostal = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datecreation = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    #[ORM\OneToMany(mappedBy: 'idAnnonce', targetEntity: ImageAnnonce::class, orphanRemoval: true)]
    private Collection $imageAnnonces;

    #[ORM\OneToMany(mappedBy: 'idAnnonce', targetEntity: AvisAnnonce::class)]
    private Collection $avisAnnonces;

    #[ORM\OneToMany(mappedBy: 'idAnnonce', targetEntity: OptionAnnonce::class, orphanRemoval: true)]
    private Collection $idLibelle;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nbmax = null;

    #[ORM\Column(nullable: true)]
    private ?int $idCategory = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255)]
    private ?string $Pays = null;

    public function __construct()
    {
        $this->imageAnnonces = new ArrayCollection();
        $this->avisAnnonces = new ArrayCollection();
        $this->idLibelle = new ArrayCollection();
        //$this->id_User = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function __toString()
    {
        return $this->id;
    }


    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->CodePostal;
    }

    public function setCodePostal(string $CodePostal): self
    {
        $this->CodePostal = $CodePostal;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): self
    {
        
        // $this->datecreation = date('d-m-Y h:i:s');
        $this->datecreation = $datecreation;

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


    public function setIdUser(User $Userid): self
    {
        $this->idUser = $Userid;

        return $this;
    }

    /**
     * @return Collection<int, ImageAnnonce>
     */
    public function getImageAnnonces(): Collection
    {
        return $this->imageAnnonces;
    }

    public function addImageAnnonce(ImageAnnonce $imageAnnonce): self
    {
        if (!$this->imageAnnonces->contains($imageAnnonce)) {
            $this->imageAnnonces->add($imageAnnonce);
            $imageAnnonce->setIdAnnonce($this);
        }

        return $this;
    }

    public function removeImageAnnonce(ImageAnnonce $imageAnnonce): self
    {
        if ($this->imageAnnonces->removeElement($imageAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($imageAnnonce->getIdAnnonce() === $this) {
                $imageAnnonce->setIdAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AvisAnnonce>
     */
    public function getAvisAnnonces(): Collection
    {
        return $this->avisAnnonces;
    }

    public function addAvisAnnonce(AvisAnnonce $avisAnnonce): self
    {
        if (!$this->avisAnnonces->contains($avisAnnonce)) {
            $this->avisAnnonces->add($avisAnnonce);
            $avisAnnonce->setIdAnnonce($this);
        }

        return $this;
    }

    public function removeAvisAnnonce(AvisAnnonce $avisAnnonce): self
    {
        if ($this->avisAnnonces->removeElement($avisAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($avisAnnonce->getIdAnnonce() === $this) {
                $avisAnnonce->setIdAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OptionAnnonce>
     */
    public function getIdLibelle(): Collection
    {
        return $this->idLibelle;
    }

    public function addIdLibelle(OptionAnnonce $idLibelle): self
    {
        if (!$this->idLibelle->contains($idLibelle)) {
            $this->idLibelle->add($idLibelle);
            $idLibelle->setIdAnnonce($this);
        }

        return $this;
    }

    public function removeIdLibelle(OptionAnnonce $idLibelle): self
    {
        if ($this->idLibelle->removeElement($idLibelle)) {
            // set the owning side to null (unless already changed)
            if ($idLibelle->getIdAnnonce() === $this) {
                $idLibelle->setIdAnnonce(null);
            }
        }

        return $this;
    }

    public function addIdUser(Reservation $idUser): self
    {
        if (!$this->id_User->contains($idUser)) {
            $this->id_User->add($idUser);
            $idUser->setIdAnnonce($this);
        }

        return $this;
    }

    public function removeIdUser(Reservation $idUser): self
    {
        if ($this->id_User->removeElement($idUser)) {
            // set the owning side to null (unless already changed)
            if ($idUser->getIdAnnonce() === $this) {
                $idUser->setIdAnnonce(null);
            }
        }

        return $this;
    }

    public function getNbmax(): ?string
    {
        return $this->nbmax;
    }

    public function setNbmax(?string $nbmax): self
    {
        $this->nbmax = $nbmax;

        return $this;
    }

    public function getIdCategory(): ?int
    {
        return $this->idCategory;
    }

    public function setIdCategory(?int $idCategory): self
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->Pays;
    }

    public function setPays(string $Pays): self
    {
        $this->Pays = $Pays;

        return $this;
    }

}
