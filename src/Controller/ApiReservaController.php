<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reserva;
use PDOException;


#[Route("/api", name:"api_")]
class ApiReservaController extends AbstractController
{
    #[Route('/getReservas/{id}', name: 'get_reservas',methods:'GET')]
    public function findAll(ReservaRepository $repo,int $id): JsonResponse
    {

        
        $reservas=$repo->getReservasUser($id);

        $datos=[];
        foreach($reservas as $reserva){
            $datos[]=[
                'id'=>$reserva->getId(),
                'fecha'=>$reserva->getFecha(),
                'asiste'=>$reserva->isAsiste(),
                'f_cancelacion'=>$reserva->getFCancelacion(),
                'mesa'=>$reserva->getMesa(),
                'user'=>$reserva->getUser(),
            ];
        }

        return $this->json(['reservas'=>$datos,'Success'=>true],201);
    }

    // #[Route('/getReserva/{id}', name: 'get_mesa',methods:'GET')]
    // public function find(ReservaRepository $repo,int $id): JsonResponse
    // {
    //     $mesa=$repo->find($id);//sí la almacena
    //     //dd($mesa);

    //     if(!$mesa){
    //         return $this->json(['message'=>'Mesa no encontrada con el id ' . $id,'Success'=>false],404);
    //     }
        
    //     $dato=[
    //         "id"=>$mesa->getId(),
    //         "alto"=>$mesa->getAlto(),
    //         "ancho"=>$mesa->getAncho(),
    //         "sillas"=>$mesa->getSillas(),
    //         "x"=>$mesa->getX(),
    //         "y"=>$mesa->getY(),
    //     ];
        

        

    //     return $this->json(['mesa'=>$dato,'Success'=>true],201);//no muestra la mesa
    // }

    // #[Route("/deleteMesa/{id}", name:"delete_mesa", methods:"DELETE")]
     
    // public function delete(int $id,EntityManagerInterface $em): Response
    // {
        
    //     $mesa = $em->getRepository(Mesa::class)->find($id);
 
    //     if (!$mesa) {
    //         return $this->json(['message'=>'Mesa no encontrada con el id: ' . $id,'Success'=>false], 404);
    //     }
 
    //     $em->remove($mesa);
    //     $em->flush();
 
    //     return $this->json(['message'=>'Mesa eliminada con exito con id: ' . $id,'Success'=>true], 202);
    // }

    
    #[Route("/putReserva", name:"edit_reserva", methods:"PUT")]
    
    public function edit(Request $request,ReservaRepository $repo,EntityManagerInterface $em): Response
    {
        $datos=json_decode($request->getContent());
        $reserva=$repo->find($datos->reserva->id);
        
 
        if (!$reserva) {
            return $this->json(['message'=>'Reserva no encontrada','Success'=>false],404);
        }
 
        $reserva->setFecha($datos->reserva->fecha);
        $reserva->setAsiste($datos->reserva->asiste);
        $reserva->setFCancelacion($datos->reserva->f_cancelacion);
        $reserva->setMesa($datos->reserva->mesa);
        $reserva->setUser($datos->reserva->user);
        
        try{
            $em->persist($reserva);
            $em->flush();
            return $this->json(['message'=>"Se ha podido modificar la reserva ",
                        'Success'=>true],202);
        }catch(PDOException){
            return $this->json(['message'=>'No se ha podido modificar la reserva','Success'=>false],404);
        }
    }

    
    #[Route("/postReserva", name:"post_reserva", methods:"POST")]
    
    public function new(Request $request,EntityManagerInterface $em): Response
    {
 
        $reserva = new Reserva();
        $reserva->setFecha($request->request->get('fecha'));
        $reserva->setAsiste(true);
        $reserva->setFCancelacion($request->request->get('FCancelacion'));
        $reserva->setMesa($request->request->get('mesa'));
        $reserva->setUser($request->request->get('user'));
 
        $em->persist($reserva);
        $em->flush();
 
        return $this->json(['message'=>'Reserva creada correctamente con el id ' . $reserva->getId(),'Success'=>true], 202);
    }
}
