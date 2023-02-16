<?php

namespace App\Entity;

use App\Repository\PresentacionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresentacionRepository::class)]
class Presentacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'presentacion')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evento $evento = null;

    #[ORM\ManyToOne(inversedBy: 'presentacion')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Juego $juego = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(?Evento $evento): self
    {
        $this->evento = $evento;

        return $this;
    }

    public function getJuego(): ?Juego
    {
        return $this->juego;
    }

    public function setJuego(?Juego $juego): self
    {
        $this->juego = $juego;

        return $this;
    }
}
