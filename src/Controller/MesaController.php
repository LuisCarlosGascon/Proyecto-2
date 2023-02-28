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
    #[Route("/mesa/formu", name:"formulario")]
    public function view(Request $request,EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MesaType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mesa = new Mesa();
            $mesa = $form->getData();
            $em->persist($mesa);
            $em->flush();
        
            return $this->json('Mesa creada con exito con el id: ' . $mesa->getId(),201);
        }
        return $this->render('mesa/formu.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route("/mesa/sala", name:"sala")]
    public function sala(Request $request,EntityManagerInterface $em): Response
    {
        return $this->render('mesa/view.html.twig');
    }

    #[Route("/mesa/reservar", name:"reservar")]
    public function reservar(Request $request,EntityManagerInterface $em): Response
    {
        return $this->render('sala/reserva.html.twig');
    }

    #[Route("/listaReservas", name:"lista_reservas")]
    public function listaReservas(Request $request,EntityManagerInterface $em): Response
    {
        return $this->render('reservas/reservasAdmin.html.twig');
    }
    

}
