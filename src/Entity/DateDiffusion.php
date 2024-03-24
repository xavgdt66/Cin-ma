<?php

namespace App\Entity;

use App\Repository\DateDiffusionRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: DateDiffusionRepository::class)] 
class DateDiffusion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;


    #[ORM\Column(type: "datetime")]
    private $date;

    #[ORM\Column(type: "time")]
    private $heureDebut;

    /********DEBUT MOVIE********************/

    #[ORM\ManyToOne(targetEntity: Movie::class, inversedBy: "dateDiffusions")] // Relation avec la "private $dateDiffusions;" de l'entity Movie
    #[ORM\JoinColumn(nullable: false)]
    private $movie;

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    /********FIN MOVIE********************/


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTimeInterface $heureDebut): self
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

  
}
