<?php

namespace App\Entity;

use App\Repository\CorrespondanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CorrespondanceRepository::class)]
class Correspondance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'correspondances')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Chien $fk_id_chien = null;

    #[ORM\ManyToOne(inversedBy: 'correspondances')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Utilisateur $fk_id_utilisateur = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $couleur = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $race = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $caractere = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFkIdUtilisateur(): ?Utilisateur
    {
        return $this->fk_id_utilisateur;
    }

    public function setFkIdUtilisateur(?Utilisateur $fk_id_utilisateur): static
    {
        $this->fk_id_utilisateur = $fk_id_utilisateur;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(?string $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getCaractere(): ?string
    {
        return $this->caractere;
    }

    public function setCaractere(?string $caractere): static
    {
        $this->caractere = $caractere;

        return $this;
    }
}
