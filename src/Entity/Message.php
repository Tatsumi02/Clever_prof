<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
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
    private $destinataire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $expediteur;

    /**
     * @ORM\Column(type="integer")
     */
    private $destinataire_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $expediteur_id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $media;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_message;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $actif;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestinataire(): ?string
    {
        return $this->destinataire;
    }

    public function setDestinataire(string $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getExpediteur(): ?string
    {
        return $this->expediteur;
    }

    public function setExpediteur(string $expediteur): self
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getDestinataireId(): ?int
    {
        return $this->destinataire_id;
    }

    public function setDestinataireId(int $destinataire_id): self
    {
        $this->destinataire_id = $destinataire_id;

        return $this;
    }

    public function getExpediteurId(): ?int
    {
        return $this->expediteur_id;
    }

    public function setExpediteurId(int $expediteur_id): self
    {
        $this->expediteur_id = $expediteur_id;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(string $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getDateMessage(): ?\DateTimeInterface
    {
        return $this->date_message;
    }

    public function setDateMessage(\DateTimeInterface $date_message): self
    {
        $this->date_message = $date_message;

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
}
