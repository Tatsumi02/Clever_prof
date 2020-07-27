<?php

namespace App\Entity;

use App\Repository\AnonceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnonceRepository::class)
 */
class Anonce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matiere;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_cours;

    /**
     * @ORM\Column(type="text")
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $parcours;

    /**
     * @ORM\Column(type="text")
     */
    private $methodologie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu_cours;

    /**
     * @ORM\Column(type="integer")
     */
    private $tarif_heure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo_profil;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $actif;

    /**
     * @ORM\Column(type="integer")
     */
    private $anonceur_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_anonce;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $certifier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getTypeCours(): ?string
    {
        return $this->type_cours;
    }

    public function setTypeCours(string $type_cours): self
    {
        $this->type_cours = $type_cours;

        return $this;
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

    public function getParcours(): ?string
    {
        return $this->parcours;
    }

    public function setParcours(string $parcours): self
    {
        $this->parcours = $parcours;

        return $this;
    }

    public function getMethodologie(): ?string
    {
        return $this->methodologie;
    }

    public function setMethodologie(string $methodologie): self
    {
        $this->methodologie = $methodologie;

        return $this;
    }

    public function getLieuCours(): ?string
    {
        return $this->lieu_cours;
    }

    public function setLieuCours(string $lieu_cours): self
    {
        $this->lieu_cours = $lieu_cours;

        return $this;
    }

    public function getTarifHeure(): ?int
    {
        return $this->tarif_heure;
    }

    public function setTarifHeure(int $tarif_heure): self
    {
        $this->tarif_heure = $tarif_heure;

        return $this;
    }

    public function getPhotoProfil(): ?string
    {
        return $this->photo_profil;
    }

    public function setPhotoProfil(string $photo_profil): self
    {
        $this->photo_profil = $photo_profil;

        return $this;
    }

    public function getActif(): ?string
    {
        return $this->actif;
    }

    public function setActif(string $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getAnonceurId(): ?int
    {
        return $this->anonceur_id;
    }

    public function setAnonceurId(int $anonceur_id): self
    {
        $this->anonceur_id = $anonceur_id;

        return $this;
    }

    public function getDateAnonce(): ?\DateTimeInterface
    {
        return $this->date_anonce;
    }

    public function setDateAnonce(\DateTimeInterface $date_anonce): self
    {
        $this->date_anonce = $date_anonce;

        return $this;
    }

    public function getCertifier(): ?string
    {
        return $this->certifier;
    }

    public function setCertifier(string $certifier): self
    {
        $this->certifier = $certifier;

        return $this;
    }
}
