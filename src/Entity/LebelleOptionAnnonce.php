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

    #[ORM\OneToMany(mappedBy: 'lebelleOptionAnnonce', targetEntity: Category::class)]
    private Collection $idCategory;

    #[ORM\OneToMany(mappedBy: 'idLibelle', targetEntity: OptionAnnonce::class, orphanRemoval: true)]
    private Collection $optionAnnonces;

    public function __construct()
    {
        $this->idCategory = new ArrayCollection();
        $this->optionAnnonces = new ArrayCollection();
    }

    public function getId(): ?int
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

    /**
     * @return Collection<int, Category>
     */
    public function getIdCategory(): Collection
    {
        return $this->idCategory;
    }

    public function addIdCategory(Category $idCategory): self
    {
        if (!$this->idCategory->contains($idCategory)) {
            $this->idCategory->add($idCategory);
            $idCategory->setLebelleOptionAnnonce($this);
        }

        return $this;
    }

    public function removeIdCategory(Category $idCategory): self
    {
        if ($this->idCategory->removeElement($idCategory)) {
            // set the owning side to null (unless already changed)
            if ($idCategory->getLebelleOptionAnnonce() === $this) {
                $idCategory->setLebelleOptionAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OptionAnnonce>
     */
    public function getOptionAnnonces(): Collection
    {
        return $this->optionAnnonces;
    }

    public function addOptionAnnonce(OptionAnnonce $optionAnnonce): self
    {
        if (!$this->optionAnnonces->contains($optionAnnonce)) {
            $this->optionAnnonces->add($optionAnnonce);
            $optionAnnonce->setIdLibelle($this);
        }

        return $this;
    }

    public function removeOptionAnnonce(OptionAnnonce $optionAnnonce): self
    {
        if ($this->optionAnnonces->removeElement($optionAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($optionAnnonce->getIdLibelle() === $this) {
                $optionAnnonce->setIdLibelle(null);
            }
        }

        return $this;
    }
}
