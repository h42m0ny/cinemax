<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SerieRepository")
 */
class Serie
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
    private $name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="text")
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $firstAirDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GenreSerie", mappedBy="series")
     */
    private $genreSeries;

    /**
     * @ORM\Column(type="integer")
     */
    private $imDBID;

    public function __construct()
    {
        $this->genreSeries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getFirstAirDate(): ?\DateTimeInterface
    {
        return $this->firstAirDate;
    }

    public function setFirstAirDate(?\DateTimeInterface $firstAirDate): self
    {
        $this->firstAirDate = $firstAirDate;

        return $this;
    }

    /**
     * @return Collection|GenreSerie[]
     */
    public function getGenreSeries(): Collection
    {
        return $this->genreSeries;
    }

    public function addGenreSeries(GenreSerie $genreSeries): self
    {
        if (!$this->genreSeries->contains($genreSeries)) {
            $this->genreSeries[] = $genreSeries;
            $genreSeries->addSeries($this);
        }

        return $this;
    }

    public function removeGenreSeries(GenreSerie $genreSeries): self
    {
        if ($this->genreSeries->contains($genreSeries)) {
            $this->genreSeries->removeElement($genreSeries);
            $genreSeries->removeSeries($this);
        }

        return $this;
    }

    public function getImDBID(): ?int
    {
        return $this->imDBID;
    }

    public function setImDBID(int $imDBID): self
    {
        $this->imDBID = $imDBID;

        return $this;
    }
}
