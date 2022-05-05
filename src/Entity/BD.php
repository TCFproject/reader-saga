<?php

namespace App\Entity;

use App\Repository\BDRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BDRepository::class)
 */
class BD
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $date_publication;

    /**
     * @ORM\ManyToOne(targetEntity=Auteur::class, inversedBy="bDs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\OneToMany(targetEntity=Cathegorie::class, mappedBy="bD")
     */
    private $cathegories;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="bDs")
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $FilePath;

    /**
     * @ORM\ManyToOne(targetEntity=Cathegorie::class, inversedBy="bDs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    public function __construct()
    {
        $this->cathegories = new ArrayCollection();
        $this->genre = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection|Cathegorie[]
     */
    public function getCathegories(): Collection
    {
        return $this->cathegories;
    }

    public function addCathegory(Cathegorie $cathegory): self
    {
        if (!$this->cathegories->contains($cathegory)) {
            $this->cathegories[] = $cathegory;
            $cathegory->setBD($this);
        }

        return $this;
    }

    public function removeCathegory(Cathegorie $cathegory): self
    {
        if ($this->cathegories->removeElement($cathegory)) {
            // set the owning side to null (unless already changed)
            if ($cathegory->getBD() === $this) {
                $cathegory->setBD(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genre->contains($genre)) {
            $this->genre[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genre->removeElement($genre);

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->FilePath;
    }

    public function setFilePath(?string $FilePath): self
    {
        $this->FilePath = $FilePath;

        return $this;
    }

    public function getCategorie(): ?Cathegorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Cathegorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
