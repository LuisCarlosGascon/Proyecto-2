<?php

namespace App\Controller;

use App\Entity\Distribucion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reserva;
use App\Repository\TramoRepository;
use App\Repository\MesaRepository;
use App\Repository\JuegoRepository;
use App\Repository\DistribucionRepository;
use App\Repository\UserRepository;
use App\Service\Telegram;
use DateTime;
use PDOException;


#[Route("/api", name:"api_")]
class ApiReservaController extends AbstractController
{
    #[Route('/getReservas/{id}', name: 'get_reservas_user',methods:'GET')]
    public function findAllUser(ReservaRepository $repo,int $id): JsonResponse
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

    #[Route('/getReservas', name: 'get_reservas',methods:'GET')]
    public function findAll(ReservaRepository $repo): JsonResponse
    {
        $reservas=$repo->findAll();

        $datos=[];
        foreach($reservas as $reserva){
            $fecha=date_format($reserva->getFecha(),'Y-m-d');
            if($reserva->getFCancelacion()!=null){
                $fechaC=date_format($reserva->getFCancelacion(),'Y-m-d');
            }else{
                $fechaC=null;
            }

            if($reserva->isAsiste()===null){
                $asiste="Esperando...";
            }else if($reserva->isAsiste()){
                $asiste="Sí";
            }else{
                $asiste="No";
            }
            
            $datos[]=[
                'id'=>$reserva->getId(),
                'fecha'=>$fecha,
                'asiste'=>$asiste,
                'f_cancelacion'=>$fechaC,
                'mesa'=>$reserva->getMesa()->__toString(),
                'user'=>$reserva->getUser()->__toString(),
                'juego'=>$reserva->getJuego()->__toString(),
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

    
    #[Route("/putAsisteReserva/{id}", name:"edit_reserva_asiste", methods:"PUT")]
    
    public function editAsiste(Request $request,ReservaRepository $repo,EntityManagerInterface $em,int $id,UserRepository $repoU): Response
    {
        $datos=json_decode($request->getContent());
        $reserva=$repo->find($id);
        $user=$this->getUser();

        if (!$reserva) {
            return $this->json(['message'=>'Reserva no encontrada','Success'=>false],404);
        }
 
        $reserva->setAsiste($datos->reserva->asiste);
        if($datos->reserva->asiste==true){
            $user->setPuntos(($user->getPuntos())+1);
        }else{
            $user->setPuntos(($user->getPuntos())-1);
        }
        
        try{
            $em->persist($reserva);
            $em->flush();
            $em->persist($user);
            $em->flush();
            return $this->json(['message'=>"Se ha podido modificar la reserva ",
                        'Success'=>true],202);
        }catch(PDOException){
            return $this->json(['message'=>'No se ha podido modificar la reserva','Success'=>false],404);
        }
    }

    
    #[Route("/postReserva", name:"post_reserva", methods:"POST")]
    
    public function new(Request $request,EntityManagerInterface $em,MesaRepository $repoM,JuegoRepository $repoJ,TramoRepository $repoT,ReservaRepository $repoR,Telegram $telegram): Response
    {
        $datos=json_decode($request->getContent());

        if($repoR->findby(array('fecha'=>new Datetime($datos->reserva->fecha))) && $repoR->findBy(array('tramo'=>$datos->reserva->tramo))){
            return $this->json(['message'=>'No se ha podido crear la reserva','Success'=>false], 202);
        }
 
        $reserva = new Reserva();
        $reserva->setFecha(new DateTime($datos->reserva->fecha));
        
        $reserva->setTramo($repoT->find($datos->reserva->tramo));
        $reserva->setAsiste(null);
        $reserva->setFCancelacion(null);
        $reserva->setMesa($repoM->find($datos->reserva->mesa));
        $reserva->setUser($this->getUser());
        $reserva->setJuego($repoJ->find($datos->reserva->juego));
 
        $em->persist($reserva);
        $em->flush();
        // $telegram->enviar($this->getUser()->getTelegram());
 
        return $this->json(['message'=>'Reserva creada correctamente con el id ' . $reserva->getId(),'Success'=>true], 202);
    }

    #[Route('/getTramos', name: 'get_tramos',methods:'GET')]
    public function tramos(ReservaRepository $repo,TramoRepository $repoT): JsonResponse
    {
        
        $tramos=$repoT->findAll();

        $datos=[];
        foreach($tramos as $tramo){
            $datos[]=[
                'id'=>$tramo->getId(),
                'hora_inicio'=>$tramo->getHora(),
                'hora_fin'=>$tramo->getHoraFin(),
            ];
        }

        return $this->json(['tramos'=>$datos,'Success'=>true],201);
    }

    #[Route('/getDistribucionReserva/{fecha}', name: 'get_distribucion_reserva',methods:'GET')]
    public function find(DistribucionRepository $repo,string $fecha): JsonResponse
    {
        $fecha2=new DateTime($fecha);
        $distribucion=$repo->findBy(array('fecha'=>$fecha2));

        if(!$distribucion){
            return $this->json(['id'=>null,'Success'=>true],202);
        }
        
        $dato=[
            "id"=>$distribucion[0]->getId(),
        ];
        return $this->json(['id'=>$dato,'Success'=>true],201);
    }
}
