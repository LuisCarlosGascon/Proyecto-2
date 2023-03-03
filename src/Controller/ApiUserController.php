<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;

#[Route("/api", name:"api_")]
class ApiUserController extends AbstractController
{
    #[Route('/getUserPuntos', name: 'get_user_puntos',methods:'GET')]
    public function puntosUser(UserRepository $repo): JsonResponse
    {
        $users=$repo->listaPuntos();

        $datos=[];
        foreach($users as $user){
            
            $datos[]=[
                'id'=>$user->getId(),
                'nombre'=>$user->getNombre(),
                'ape1'=>$user->getApe1(),
                'ape2'=>$user->getApe2(),
                'telefono'=>$user->getTelefono(),
                'telegram'=>$user->getTelegram(),
                'email'=>$user->getEmail(),
                'rol'=>$user->getRoles(),
                'imagen'=>$user->getImagen()
            ];
        }

        return $this->json(['users'=>$datos,'Success'=>true],201);
    }
}
