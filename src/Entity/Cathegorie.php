<?php

namespace App\Entity;

use App\Repository\CathegorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CathegorieRepository::class)
 */
class Cathegorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=BD::class, mappedBy="categorie")
     */
    private $bDs;

    public function __construct()
    {
        $this->bDs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function setBD(?BD $bD): self
    {
        $this->bD = $bD;

        return $this;
    }

    /**
     * @return Collection|BD[]
     */
    public function getBDs(): Collection
    {
        return $this->bDs;
    }

    public function addBD(BD $bD): self
    {
        if (!$this->bDs->contains($bD)) {
            $this->bDs[] = $bD;
            $bD->setCategorie($this);
        }

        return $this;
    }

    public function removeBD(BD $bD): self
    {
        if ($this->bDs->removeElement($bD)) {
            // set the owning side to null (unless already changed)
            if ($bD->getCategorie() === $this) {
                $bD->setCategorie(null);
            }
        }

        return $this;
    }
}
