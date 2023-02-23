<?php

namespace App\Entity;

use App\Repository\MesaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MesaRepository::class)]
class Mesa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $alto = null;

    #[ORM\Column]
    private ?float $ancho = null;

    #[ORM\Column]
    private ?int $sillas = null;

    #[ORM\Column(nullable: true)]
    private ?float $x = null;

    #[ORM\Column(nullable: true)]
    private ?float $y = null;

    #[ORM\OneToMany(mappedBy: 'mesa', targetEntity: Reserva::class)]
    private Collection $reserva;

    #[ORM\OneToMany(mappedBy: 'mesa', targetEntity: Distribuciones::class)]
    private Collection $distribucion;

    public function __construct()
    {
        $this->reserva = new ArrayCollection();
        $this->distribucion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlto(): ?float
    {
        return $this->alto;
    }

    public function setAlto(float $alto): self
    {
        $this->alto = $alto;

        return $this;
    }

    public function getAncho(): ?float
    {
        return $this->ancho;
    }

    public function setAncho(float $ancho): self
    {
        $this->ancho = $ancho;

        return $this;
    }

    public function getSillas(): ?int
    {
        return $this->sillas;
    }

    public function setSillas(int $sillas): self
    {
        $this->sillas = $sillas;

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

    /**
     * @return Collection<int, Reserva>
     */
    public function getReserva(): Collection
    {
        return $this->reserva;
    }

    public function addReserva(Reserva $reserva): self
    {
        if (!$this->reserva->contains($reserva)) {
            $this->reserva->add($reserva);
            $reserva->setMesa($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reserva->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getMesa() === $this) {
                $reserva->setMesa(null);
            }
        }

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
            $distribucion->setMesa($this);
        }

        return $this;
    }

    public function removeDistribucion(Distribuciones $distribucion): self
    {
        if ($this->distribucion->removeElement($distribucion)) {
            // set the owning side to null (unless already changed)
            if ($distribucion->getMesa() === $this) {
                $distribucion->setMesa(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->alto." x ".$this->ancho; 
    }
}
