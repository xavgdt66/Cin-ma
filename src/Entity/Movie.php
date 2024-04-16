<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Titre = null;




    #[ORM\Column(type: 'string', nullable:true)]
    private ?string $brochureFilename = null;

    #[ORM\ManyToMany(targetEntity: Salle::class)]
    private Collection $salles;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "movies")] // Relation avec la "private $movies;" de l'entity User
    #[ORM\JoinColumn(nullable: false)]
    private $user;



    /********DEBUT DATE DIFFUSION********************/
    #[ORM\OneToMany(targetEntity: DateDiffusion::class, mappedBy: "movie", cascade: ['persist','remove'])] // Relation avec la "private $movie;" de l'entity DateDiffusion

    private $dateDiffusions;

      
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy:"movie", cascade: ["persist", "remove"])]
     
    private $reservations;

    public function __construct()
    {
        $this->dateDiffusions = new ArrayCollection();
        $this->salles = new ArrayCollection();
        $this->reservations = new ArrayCollection();

    }

    /**
     * @return Collection|DateDiffusion[]
     */
    public function getDateDiffusions(): Collection
    {
        return $this->dateDiffusions;
    }

    public function addDateDiffusion(DateDiffusion $dateDiffusion): self
    {
        if (!$this->dateDiffusions->contains($dateDiffusion)) {
            $this->dateDiffusions[] = $dateDiffusion;
            $dateDiffusion->setMovie($this);
        }

        return $this;
    }

    public function removeDateDiffusion(DateDiffusion $dateDiffusion): self
    {
        if ($this->dateDiffusions->removeElement($dateDiffusion)) {
            if ($dateDiffusion->getMovie() === $this) {
                $dateDiffusion->setMovie(null);
            }
        }

        return $this;
    }


    /********FIN DATE DIFFUSION********************/



    /********DEBUT  SALLES********************/

    /**
     * @return Collection|Salle[]
     */
    public function getSalles(): Collection
    {
        return $this->salles;
    }

    public function addSalle(Salle $salle): self
    {
        if (!$this->salles->contains($salle)) {
            $this->salles[] = $salle;
        }

        return $this;
    }

    public function removeSalle(Salle $salle): self
    {
        $this->salles->removeElement($salle);

        return $this;
    }
    /********FIN SALLES********************/

    /********DEBUT USER ********************/

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /********FIN USER********************/



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getBrochureFilename(): ?string
    {
        return $this->brochureFilename;
    }

    public function setBrochureFilename(?string $brochureFilename): self
    {
        $this->brochureFilename = $brochureFilename;

        return $this;
    }



     /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setMovie($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // Définissez le côté inverse sur null (à supprimer uniquement si nécessaire)
            if ($reservation->getMovie() === $this) {
                $reservation->setMovie(null);
            }
        }

        return $this;
    }
}
