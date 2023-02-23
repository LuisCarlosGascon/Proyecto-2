<?php

namespace App\Entity;

use App\Repository\DistribucionesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DistribucionesRepository::class)]
class Distribuciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'distribucion')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Distribucion $distribucion = null;

    #[ORM\ManyToOne(inversedBy: 'distribucion')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mesa $mesa = null;

    #[ORM\Column]
    private ?float $x = null;

    #[ORM\Column]
    private ?float $y = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDistribucion(): ?Distribucion
    {
        return $this->distribucion;
    }

    public function setDistribucion(?Distribucion $distribucion): self
    {
        $this->distribucion = $distribucion;

        return $this;
    }

    public function getMesa(): ?Mesa
    {
        return $this->mesa;
    }

    public function setMesa(?Mesa $mesa): self
    {
        $this->mesa = $mesa;

        return $this;
    }

    public function getX(): ?float
    {
        return $this->x;
    }

    public function setX(?float $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?float
    {
        return $this->y;
    }

    public function setY(?float $y): self
    {
        $this->y = $y;

        return $this;
    }
}
