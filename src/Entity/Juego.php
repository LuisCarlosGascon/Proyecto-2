<?php

namespace App\Entity;

use App\Repository\JuegoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JuegoRepository::class)]
class Juego
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $min_jugadores = null;

    #[ORM\Column]
    private ?int $max_jugadores = null;

    #[ORM\OneToMany(mappedBy: 'juego', targetEntity: Presentacion::class)]
    private Collection $presentacion;

    #[ORM\Column]
    private ?float $alto = null;

    #[ORM\Column]
    private ?float $ancho = null;

    #[ORM\Column(length: 130)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagen = null;

    public function __construct()
    {
        $this->presentacion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getMinJugadores(): ?int
    {
        return $this->min_jugadores;
    }

    public function setMinJugadores(int $min_jugadores): self
    {
        $this->min_jugadores = $min_jugadores;

        return $this;
    }

    public function getMaxJugadores(): ?int
    {
        return $this->max_jugadores;
    }

    public function setMaxJugadores(int $max_jugadores): self
    {
        $this->max_jugadores = $max_jugadores;

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
            $presentacion->setJuego($this);
        }

        return $this;
    }

    public function removePresentacion(Presentacion $presentacion): self
    {
        if ($this->presentacion->removeElement($presentacion)) {
            // set the owning side to null (unless already changed)
            if ($presentacion->getJuego() === $this) {
                $presentacion->setJuego(null);
            }
        }

        return $this;
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getDimensiones(){
        return $this->ancho." x ".$this->alto;
    }

    public function acortaDescripcion(){
        return substr($this->descripcion,0,60)."...";
    }
}
