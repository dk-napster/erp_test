<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrenciesRepository")
 */
class Currencies
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $base;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $related;

    /**
     * @ORM\Column(type="decimal", precision=62, scale=2)
     */
    private $rate;

    /**
     * @ORM\Column(type="datetime", name="date_created")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="datetime", name="timestamp_created")
     */
    private $timestampCreated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBase(): ?string
    {
        return $this->base;
    }

    public function setBase(string $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getRelated(): ?string
    {
        return $this->related;
    }

    public function setRelated(string $related): self
    {
        $this->related = $related;

        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getTimestampCreated(): ?\DateTimeInterface
    {
        return $this->timestampCreated;
    }

    public function setTimestampCreated(\DateTimeInterface $timestampCreated): self
    {
        $this->timestampCreated = $timestampCreated;

        return $this;
    }
}
