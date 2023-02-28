<?php

namespace App\Entity;

use App\Repository\EventoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventoRepository::class)]
class Evento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?int $num_asistentes_max = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nombre = null;

    #[ORM\OneToMany(mappedBy: 'evento', targetEntity: Invitacion::class)]
    private Collection $invitacion;

    #[ORM\OneToMany(mappedBy: 'evento', targetEntity: Presentacion::class)]
    private Collection $presentacion;

    #[ORM\Column(length: 255)]
    private ?string $imagen = null;

    #[ORM\ManyToOne(inversedBy: 'evento')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramo $tramo = null;

    public function __construct()
    {
        $this->invitacion = new ArrayCollection();
        $this->presentacion = new ArrayCollection();
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

    public function getNumAsistentesMax(): ?int
    {
        return $this->num_asistentes_max;
    }

    public function setNumAsistentesMax(int $num_asistentes_max): self
    {
        $this->num_asistentes_max = $num_asistentes_max;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Invitacion>
     */
    public function getInvitacion(): Collection
    {
        return $this->invitacion;
    }

    public function addInvitacion(Invitacion $invitacion): self
    {
        if (!$this->invitacion->contains($invitacion)) {
            $this->invitacion->add($invitacion);
            $invitacion->setEvento($this);
        }

        return $this;
    }

    public function removeInvitacion(Invitacion $invitacion): self
    {
        if ($this->invitacion->removeElement($invitacion)) {
            // set the owning side to null (unless already changed)
            if ($invitacion->getEvento() === $this) {
                $invitacion->setEvento(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Presentacion>
     */
    public function getPresentacion(): Collection
    {
        return $this->presentacion;
    }

    public function addPresentacion(Presentacion $presentacion): self
    {
        if (!$this->presentacion->contains($presentacion)) {
            $this->presentacion->add($presentacion);
            $presentacion->setEvento($this);
        }

        return $this;
    }

    public function removePresentacion(Presentacion $presentacion): self
    {
        if ($this->presentacion->removeElement($presentacion)) {
            // set the owning side to null (unless already changed)
            if ($presentacion->getEvento() === $this) {
                $presentacion->setEvento(null);
            }
        }

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getTramo(): ?Tramo
    {
        return $this->tramo;
    }

    public function setTramo(?Tramo $tramo): self
    {
        $this->tramo = $tramo;

        return $this;
    }

    public function fechaString(){
        return date_format($this->fecha,'Y-m-d');
    }
}
