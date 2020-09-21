<?php

namespace App\Entity;

use App\Repository\PourcentagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PourcentagesRepository::class)
 */
class Pourcentages
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
    private $pourcentage;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_pourcentage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPourcentage(): ?int
    {
        return $this->pourcentage;
    }

    public function setPourcentage(int $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function getDatePourcentage(): ?\DateTimeInterface
    {
        return $this->date_pourcentage;
    }

    public function setDatePourcentage(\DateTimeInterface $date_pourcentage): self
    {
        $this->date_pourcentage = $date_pourcentage;

        return $this;
    }
}
