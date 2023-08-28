<?php

namespace App\Entity;

use App\Repository\ChienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChienRepository::class)]
class Chien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(length: 50)]
    private ?string $race = null;

    #[ORM\Column(length: 50)]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column]
    private ?int $taille = null;

    #[ORM\Column]
    private ?int $poids = null;

    #[ORM\Column(length: 50)]
    private ?string $caractere = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'chiens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Admin $fk_id_admin = null;

    #[ORM\OneToMany(mappedBy: 'fk_id_chien', targetEntity: Correspondance::class)]
    private Collection $correspondances;

    #[ORM\OneToMany(mappedBy: 'fk_id_chien', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    public function __construct()
    {
        $this->correspondances = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(string $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getCaractere(): ?string
    {
        return $this->caractere;
    }

    public function setCaractere(string $caractere): static
    {
        $this->caractere = $caractere;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFkIdAdmin(): ?Admin
    {
        return $this->fk_id_admin;
    }

    public function setFkIdAdmin(?Admin $fk_id_admin): static
    {
        $this->fk_id_admin = $fk_id_admin;

        return $this;
    }

    /**
     * @return Collection<int, Correspondance>
     */
    public function getCorrespondances(): Collection
    {
        return $this->correspondances;
    }

    public function addCorrespondance(Correspondance $correspondance): static
    {
        if (!$this->correspondances->contains($correspondance)) {
            $this->correspondances->add($correspondance);
            $correspondance->setFkIdChien($this);
        }

        return $this;
    }

    public function removeCorrespondance(Correspondance $correspondance): static
    {
        if ($this->correspondances->removeElement($correspondance)) {
            // set the owning side to null (unless already changed)
            if ($correspondance->getFkIdChien() === $this) {
                $correspondance->setFkIdChien(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setFkIdChien($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFkIdChien() === $this) {
                $commentaire->setFkIdChien(null);
            }
        }

        return $this;
    }
}
