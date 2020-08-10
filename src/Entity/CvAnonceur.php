<?php

namespace App\Entity;

use App\Repository\CvAnonceurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CvAnonceurRepository::class)
 */
class CvAnonceur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $anonceur_id;

    /**
     * @ORM\Column(type="text")
     */
    private $diplome;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_enregistrement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $actif;

    /**
     * @ORM\Column(type="integer")
     */
    private $anonce_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->date_enregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $date_enregistrement): self
    {
        $this->date_enregistrement = $date_enregistrement;

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

    public function getAnonceId(): ?int
    {
        return $this->anonce_id;
    }

    public function setAnonceId(int $anonce_id): self
    {
        $this->anonce_id = $anonce_id;

        return $this;
    }
}
