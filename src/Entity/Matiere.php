<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 */
class Matiere
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
    private $cours;

    /**
     * @ORM\Column(type="text")
     */
    private $branche;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_ajout;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCours(): ?string
    {
        return $this->cours;
    }

    public function setCours(string $cours): self
    {
        $this->cours = $cours;

        return $this;
    }

    public function getBranche(): ?string
    {
        return $this->branche;
    }

    public function setBranche(string $branche): self
    {
        $this->branche = $branche;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->date_ajout;
    }

    public function setDateAjout(\DateTimeInterface $date_ajout): self
    {
        $this->date_ajout = $date_ajout;

        return $this;
    }
}
