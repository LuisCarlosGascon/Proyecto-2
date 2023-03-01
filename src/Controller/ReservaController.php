<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ReservaController extends AbstractController
{
    #[Route("/mesaReservar", name:"reservar")]
    public function reservar(Request $request,EntityManagerInterface $em): Response
    {
        return $this->render('reserva/reservar.html.twig');
    }

    #[Route("/listaReservas", name:"lista_reservas")]
    public function listaReservas(Request $request,EntityManagerInterface $em): Response
    {
        return $this->render('reserva/reservasAdmin.html.twig');
    }
}
