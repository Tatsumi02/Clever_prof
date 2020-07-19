<?php

namespace App\Entity;

use App\Repository\AnonceVueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnonceVueRepository::class)
 */
class AnonceVue
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
    private $visiteur_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $anonce_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_vue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisiteurId(): ?int
    {
        return $this->visiteur_id;
    }

    public function setVisiteurId(int $visiteur_id): self
    {
        $this->visiteur_id = $visiteur_id;

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

    public function getDateVue(): ?\DateTimeInterface
    {
        return $this->date_vue;
    }

    public function setDateVue(\DateTimeInterface $date_vue): self
    {
        $this->date_vue = $date_vue;

        return $this;
    }
}
