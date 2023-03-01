<?php

namespace App\Controller;

use App\Entity\Mesa;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class MesaController extends AbstractController
{

    #[Route("/mesasSala", name:"sala")]
    public function sala(Request $request,EntityManagerInterface $em): Response
    {
        return $this->render('mesa/mantenimiento.html.twig');
    }

}
