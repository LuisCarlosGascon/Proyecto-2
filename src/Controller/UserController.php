<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class UserController extends AbstractController
{
    #[Route('/misReservas/{id}', name: 'app_user_reservas')]
    public function index(ReservaRepository $repo,int $id,UserRepository $repoU): Response
    {
        $user=$repoU->find($id);
        $reservas=$repo->findBy(array("user"=>$user));

        return $this->render('user/reservas.html.twig',[
            'reservas'=>$reservas
        ]);
    }

    #[Route('/cancelarMiReserva/{id}', name: 'reserva_cancelar')]
    public function eliminar(ReservaRepository $repo,EntityManagerInterface $em,int $id): Response
    {
        $reserva=$repo->find($id);
        $reserva->setFCancelacion(new DateTime(date('Y-m-d')));
        $em->persist($reserva);
        $em->flush();

        return $this->redirect('/misReservas/'.$this->getUser()->getId());
    }

    
}
