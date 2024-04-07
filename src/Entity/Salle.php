<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;


    #[ORM\Column(type: "string", length: 255)]
    private $nom;


    #[ORM\Column(type: "integer")]

    private $nombrePlaces;

    ///////////////////////DEBUT CINEMA /////////////////////////////////////////////////////////
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "salles")] // Relation avec la "private $salles;" de l'entity User
    #[ORM\JoinColumn(nullable: false)]

    private $user;

    public function getUser(): ?User
    {
        return $this->user;
    }
    
    public function setUser(?User $user): self
    {
        $this->user = $user;
    
        return $this;
    }
    ///////////////////////////// FIN CINEMA ///////////////////////////////////////////////////

    public function __toString(): string // // Convertie en string getNom pour le crud admin de movie 
    {
        return $this->getNom(); 
    }
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
}
