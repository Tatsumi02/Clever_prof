<?php

namespace App\Entity;

use App\Repository\NotionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotionRepository::class)
 */
class Notion
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
    private $annonceur_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $annonce_id;

    /**
     * @ORM\Column(type="text")
     */
    private $notion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accomplir;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_notion;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAnnonceId(): ?int
    {
        return $this->annonce_id;
    }

    public function setAnnonceId(int $annonce_id): self
    {
        $this->annonce_id = $annonce_id;

        return $this;
    }

    public function getNotion(): ?string
    {
        return $this->notion;
    }

    public function setNotion(string $notion): self
    {
        $this->notion = $notion;

        return $this;
    }

    public function getAccomplir(): ?string
    {
        return $this->accomplir;
    }

    public function setAccomplir(string $accomplir): self
    {
        $this->accomplir = $accomplir;

        return $this;
    }

    public function getDateNotion(): ?\DateTimeInterface
    {
        return $this->date_notion;
    }

    public function setDateNotion(\DateTimeInterface $date_notion): self
    {
        $this->date_notion = $date_notion;

        return $this;
    }
}
