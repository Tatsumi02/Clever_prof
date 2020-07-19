<?php

namespace App\Entity;

use App\Repository\ReserveCoursRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReserveCoursRepository::class)
 */
class ReserveCours
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message_contact;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $format_cours;

    /**
     * @ORM\Column(type="text")
     */
    private $coordonnee_contact;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mode_payement;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_reservation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageContact(): ?string
    {
        return $this->message_contact;
    }

    public function setMessageContact(string $message_contact): self
    {
        $this->message_contact = $message_contact;

        return $this;
    }

    public function getFormatCours(): ?string
    {
        return $this->format_cours;
    }

    public function setFormatCours(string $format_cours): self
    {
        $this->format_cours = $format_cours;

        return $this;
    }

    public function getCoordonneeContact(): ?string
    {
        return $this->coordonnee_contact;
    }

    public function setCoordonneeContact(string $coordonnee_contact): self
    {
        $this->coordonnee_contact = $coordonnee_contact;

        return $this;
    }

    public function getModePayement(): ?string
    {
        return $this->mode_payement;
    }

    public function setModePayement(string $mode_payement): self
    {
        $this->mode_payement = $mode_payement;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(\DateTimeInterface $date_reservation): self
    {
        $this->date_reservation = $date_reservation;

        return $this;
    }
}
