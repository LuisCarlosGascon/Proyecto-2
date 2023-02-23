<?php

namespace App\Entity;

use App\Repository\TramoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TramoRepository::class)]
class Tramo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hora_inicio = null;

    #[ORM\OneToMany(mappedBy: 'tramo', targetEntity: Reserva::class)]
    private Collection $reserva;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hora_fin = null;

    #[ORM\OneToMany(mappedBy: 'tramo', targetEntity: Evento::class)]
    private Collection $evento;

    public function __construct()
    {
        $this->reserva = new ArrayCollection();
        $this->evento = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora_inicio;
    }

    public function setHora(\DateTimeInterface $hora_inicio): self
    {
        $this->hora_inicio = $hora_inicio;

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
            $reserva->setTramo($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reserva->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getTramo() === $this) {
                $reserva->setTramo(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->hora_inicio->format("H:i")." - ".$this->hora_fin->format("H:i");
    }

    public function getHoraFin(): ?\DateTimeInterface
    {
        return $this->hora_fin;
    }

    public function setHoraFin(\DateTimeInterface $hora_fin): self
    {
        $this->hora_fin = $hora_fin;

        return $this;
    }

    /**
     * @return Collection<int, Evento>
     */
    public function getEvento(): Collection
    {
        return $this->evento;
    }

    public function addEvento(Evento $evento): self
    {
        if (!$this->evento->contains($evento)) {
            $this->evento->add($evento);
            $evento->setTramo($this);
        }

        return $this;
    }

    public function removeEvento(Evento $evento): self
    {
        if ($this->evento->removeElement($evento)) {
            // set the owning side to null (unless already changed)
            if ($evento->getTramo() === $this) {
                $evento->setTramo(null);
            }
        }

        return $this;
    }
}
