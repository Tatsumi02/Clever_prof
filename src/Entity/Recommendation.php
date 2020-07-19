<?php

namespace App\Entity;

use App\Repository\RecommendationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecommendationRepository::class)
 */
class Recommendation
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
    private $anonce_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mode_recommendation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_recommendation;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getModeRecommendation(): ?string
    {
        return $this->mode_recommendation;
    }

    public function setModeRecommendation(string $mode_recommendation): self
    {
        $this->mode_recommendation = $mode_recommendation;

        return $this;
    }

    public function getDateRecommendation(): ?\DateTimeInterface
    {
        return $this->date_recommendation;
    }

    public function setDateRecommendation(\DateTimeInterface $date_recommendation): self
    {
        $this->date_recommendation = $date_recommendation;

        return $this;
    }
}
