<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
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
     * @ORM\Column(type="integer")
     */
    private $reserveur_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $anonce_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lien;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_notification;

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

    public function getReserveurId(): ?int
    {
        return $this->reserveur_id;
    }

    public function setReserveurId(int $reserveur_id): self
    {
        $this->reserveur_id = $reserveur_id;

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

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateNotification(): ?\DateTimeInterface
    {
        return $this->date_notification;
    }

    public function setDateNotification(\DateTimeInterface $date_notification): self
    {
        $this->date_notification = $date_notification;

        return $this;
    }
}
