<?php

namespace App\Entity;

use App\Repository\SeasonSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeasonSettingsRepository::class)
 */
class SeasonSettings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $radwechselEnabled;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $currentBanner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRadwechselEnabled(): ?bool
    {
        return $this->radwechselEnabled;
    }

    public function setRadwechselEnabled(bool $radwechselEnabled): self
    {
        $this->radwechselEnabled = $radwechselEnabled;

        return $this;
    }

    public function getCurrentBanner(): ?string
    {
        return $this->currentBanner;
    }

    public function setCurrentBanner(string $currentBanner): self
    {
        $this->currentBanner = $currentBanner;

        return $this;
    }
}
