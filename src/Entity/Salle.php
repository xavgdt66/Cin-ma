<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;


    #[ORM\Column(type: "string", length: 255)]
    private $nom;


    #[ORM\Column(type: "integer")]

    private $nombrePlaces;

    ////////////////////////////////////////////////////////////////////////////////
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "salles")]
    #[ORM\JoinColumn(nullable: false)]

    private $cinema;
    ////////////////////////////////////////////////////////////////////////////////


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNombrePlaces(): ?int
    {
        return $this->nombrePlaces;
    }

    public function setNombrePlaces(int $nombrePlaces): self
    {
        $this->nombrePlaces = $nombrePlaces;

        return $this;
    }

    public function getCinema(): ?User
    {
        return $this->cinema;
    }

    public function setCinema(?User $cinema): self
    {
        $this->cinema = $cinema;

        return $this;
    }
}
