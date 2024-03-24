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
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Titre = null;

    /********DEBUT DATE DIFFUSION********************/
    #[ORM\OneToMany(targetEntity: DateDiffusion::class, mappedBy: "movie")] // Relation avec la "private $movie;" de l'entity DateDiffusion

    private $dateDiffusions;

    public function __construct()
    {
        $this->dateDiffusions = new ArrayCollection();
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
}
