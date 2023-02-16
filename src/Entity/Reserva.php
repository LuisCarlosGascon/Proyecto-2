<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(nullable: true)]
    private ?bool $asiste = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $f_cancelacion = null;

    #[ORM\ManyToOne(inversedBy: 'reserva')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mesa $mesa = null;

    #[ORM\ManyToOne(inversedBy: 'reserva')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

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

    public function isAsiste(): ?bool
    {
        return $this->asiste;
    }

    public function setAsiste(?bool $asiste): self
    {
        $this->asiste = $asiste;

        return $this;
    }

    public function getFCancelacion(): ?\DateTimeInterface
    {
        return $this->f_cancelacion;
    }

    public function setFCancelacion(?\DateTimeInterface $f_cancelacion): self
    {
        $this->f_cancelacion = $f_cancelacion;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __toString(){
        return $this->fecha;
    }

    public function getFechaString(){
        return $this->fecha->format('Y-m-d H:i:s');
    }

    public function getFechaC_String(){    
        return $this->f_cancelacion->format('Y-m-d H:i:s');
    }

}