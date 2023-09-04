<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $texte = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isValide = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Chien $fk_id_chien = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Admin $fk_id_admin = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Utilisateur $fk_id_utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->isValide;
    }

    public function setIsValide(bool $isValide): static
    {
        $this->isValide = $isValide;

        return $this;
    }

    public function getFkIdChien(): ?Chien
    {
        return $this->fk_id_chien;
    }

    public function setFkIdChien(?Chien $fk_id_chien): static
    {
        $this->fk_id_chien = $fk_id_chien;

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

    public function getFkIdUtilisateur(): ?Utilisateur
    {
        return $this->fk_id_utilisateur;
    }

    public function setFkIdUtilisateur(?Utilisateur $fk_id_utilisateur): static
    {
        $this->fk_id_utilisateur = $fk_id_utilisateur;

        return $this;
    }
}
