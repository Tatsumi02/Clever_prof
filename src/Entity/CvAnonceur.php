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
}
