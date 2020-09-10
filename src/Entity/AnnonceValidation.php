<?php

namespace App\Entity;

use App\Repository\AnnonceValidationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceValidationRepository::class)
 */
class AnnonceValidation
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
    private $annonce_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $annonceur_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $public;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnonceId(): ?int
    {
        return $this->annonce_id;
    }

    public function setAnnonceId(int $annonce_id): self
    {
        $this->annonce_id = $annonce_id;

        return $this;
    }

    public function getAnnonceurId(): ?int
    {
        return $this->annonceur_id;
    }

    public function setAnnonceurId(int $annonceur_id): self
    {
        $this->annonceur_id = $annonceur_id;

        return $this;
    }

    public function getPublic(): ?string
    {
        return $this->public;
    }

    public function setPublic(string $public): self
    {
        $this->public = $public;

        return $this;
    }
}
