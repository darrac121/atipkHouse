<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];


    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 25)]
    private ?string $telephone = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recup_mdp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\OneToMany(mappedBy: 'idUser', targetEntity: DocumentProprietaire::class)]
    #[ORM\JoinColumn( nullable: true)]
    private Collection $documentProprietaires;

    #[ORM\OneToMany(mappedBy: 'idUser', targetEntity: Annonce::class)]
    #[ORM\JoinColumn( nullable: true)]
    private Collection $annonces;

    #[ORM\OneToMany(mappedBy: 'idUser', targetEntity: Reservation::class)]
    private Collection $id_user_reservation;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Name = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column]
    private ?int $status = null;


    // #[ORM\Column(type: 'boolean')]
    // private $isVerified = false;

    public function __construct()
    {
        $this->documentProprietaires = new ArrayCollection();
        $this->annonces = new ArrayCollection();
        $this->id_user_reservation = new ArrayCollection();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString()
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    

    public function getRecupMdp(): ?string
    {
        return $this->recup_mdp;
    }

    public function setRecupMdp(?string $recup_mdp): self
    {
        $this->recup_mdp = $recup_mdp;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Collection<int, DocumentProprietaire>
     */
    public function getDocumentProprietaires(): Collection
    {
        return $this->documentProprietaires;
    }

    public function addDocumentProprietaire(DocumentProprietaire $documentProprietaire): self
    {
        if (!$this->documentProprietaires->contains($documentProprietaire)) {
            $this->documentProprietaires->add($documentProprietaire);
            $documentProprietaire->setIdUser($this);
        }

        return $this;
    }

    public function removeDocumentProprietaire(DocumentProprietaire $documentProprietaire): self
    {
        if ($this->documentProprietaires->removeElement($documentProprietaire)) {
            // set the owning side to null (unless already changed)
            if ($documentProprietaire->getIdUser() === $this) {
                $documentProprietaire->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces->add($annonce);
            $annonce->setIdUser($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getIdUser() === $this) {
                $annonce->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getIdUserReservation(): Collection
    {
        return $this->id_user_reservation;
    }

    public function addIdUserReservation(Reservation $idUserReservation): self
    {
        if (!$this->id_user_reservation->contains($idUserReservation)) {
            $this->id_user_reservation->add($idUserReservation);
            $idUserReservation->setIdUser($this);
        }

        return $this;
    }

    public function removeIdUserReservation(Reservation $idUserReservation): self
    {
        if ($this->id_user_reservation->removeElement($idUserReservation)) {
            // set the owning side to null (unless already changed)
            if ($idUserReservation->getIdUser() === $this) {
                $idUserReservation->setIdUser(null);
            }
        }

        return $this;
    }


    /*
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    */

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
    
    
}
