<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

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

    #[Route('/eliminarMiReserva/{id}', name: 'reserva_eliminar')]
    public function eliminar(ReservaRepository $repo,EntityManagerInterface $em,int $id): Response
    {
        $reserva=$repo->find($id);
        $em->remove($reserva);
        $em->flush();

        return $this->redirect('/misReservas/'.$this->getUser()->getId());
    }

    
}
