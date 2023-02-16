<?php

namespace App\Entity;

use App\Repository\DistribucionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DistribucionRepository::class)]
class Distribucion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\OneToMany(mappedBy: 'distribucion', targetEntity: Distribuciones::class)]
    private Collection $distribucion;

    public function __construct()
    {
        $this->distribucion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return Collection<int, Distribuciones>
     */
    public function getDistribucion(): Collection
    {
        return $this->distribucion;
    }

    public function addDistribucion(Distribuciones $distribucion): self
    {
        if (!$this->distribucion->contains($distribucion)) {
            $this->distribucion->add($distribucion);
            $distribucion->setDistribucion($this);
        }

        return $this;
    }

    public function removeDistribucion(Distribuciones $distribucion): self
    {
        if ($this->distribucion->removeElement($distribucion)) {
            // set the owning side to null (unless already changed)
            if ($distribucion->getDistribucion() === $this) {
                $distribucion->setDistribucion(null);
            }
        }

        return $this;
    }
}
