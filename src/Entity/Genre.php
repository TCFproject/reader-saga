<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre
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
     * @ORM\ManyToMany(targetEntity=BD::class, mappedBy="genre")
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
            $bD->addGenre($this);
        }

        return $this;
    }

    public function removeBD(BD $bD): self
    {
        if ($this->bDs->removeElement($bD)) {
            $bD->removeGenre($this);
        }

        return $this;
    }
}
