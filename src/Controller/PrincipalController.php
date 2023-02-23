<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evento;
use App\Entity\Juego;
use App\Repository\EventoRepository;
use App\Repository\JuegoRepository;
use Symfony\Component\Validator\Constraints\Length;

class PrincipalController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(EventoRepository $repoEvento,JuegoRepository $repoJuego): Response
    {
        $eventos=$repoEvento->findAll();
        $juego=$repoJuego->getJuegoRandom();
        
        return $this->render('principal/index.html.twig',[
            'eventos'=>$eventos,
            'juego'=>$juego,
        ]);
    }
}
