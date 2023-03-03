<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Evento;
use App\Entity\Presentacion;
use App\Repository\TramoRepository;
use App\Repository\JuegoRepository;
use App\Repository\PresentacionRepository;
use App\Entity\Invitacion;
use App\Repository\UserRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use PDOException;
use DateTime;

#[Route("/api", name:"api_")]
class ApiEventoController extends AbstractController
{

    #[Route('/getEventos', name: 'get_eventos',methods:'GET')]
    public function findAll(EventoRepository $repo): JsonResponse
    {
    $eventos=$repo->findAll();

        $datos=[];

        foreach($eventos as $evento){
            $fecha=date_format($evento->getFecha(),'Y-m-d');
            $datos[]=[
                'id'=>$evento->getId(),
                'nombre'=>$evento->getNombre(),
                'fecha'=>$fecha,
                'num_asistentes_max'=>$evento->getNumAsistentesMax(),
                'imagen'=>$evento->getImagen(),
            ];
        }

        return $this->json(['eventos'=>$datos,'Success'=>true],201);
    }

    #[Route('/getEvento/{id}', name: 'get_evento',methods:'GET')]
    public function find(EventoRepository $repo,int $id): JsonResponse
    {
        $evento=$repo->find($id);
    

        if(!$evento){
            return $this->json(['message'=>'Evento no encontrado con el id ' . $id,'Success'=>false],404);
        }
        
        $fecha=date_format($evento->getFecha(),'Y-m-d');
        $dato[]=[
            'id'=>$evento->getId(),
            'nombre'=>$evento->getNombre(),
            'fecha'=>$fecha,
            'num_asistentes_max'=>$evento->getNumAsistentesMax(),
            'imagen'=>$evento->getImagen(),
        ];
        

        

        return $this->json(['evento'=>$dato,'Success'=>true],201);
    }

    #[Route("/deleteEvento/{id}", name:"delete_evento", methods:"DELETE")]
     
    public function delete(int $id,EntityManagerInterface $em): Response
    {
        
        $evento = $em->getRepository(Evento::class)->find($id);
 
        if (!$evento) {
            return $this->json(['message'=>'Mesa no encontrada con el id: ' . $id,'Success'=>false], 404);
        }
 
        $em->remove($evento);
        $em->flush();
 
        return $this->json(['message'=>'Evento eliminado con exito con id: ' . $id,'Success'=>true], 202);
    }

    
    #[Route("/putEvento", name:"edit_evento", methods:"PUT")]
    
    public function edit(Request $request,EventoRepository $repo,EntityManagerInterface $em): Response
    {
        $datos=json_decode($request->getContent());
        $evento=$repo->find($datos->evento->id);
        
 
        if (!$evento) {
            return $this->json(['message'=>'Evento no encontrado','Success'=>false],404);
        }
 
        $evento->setNombre($datos->evento->nombre);
        $evento->setFecha($datos->evento->fecha);
        $evento->setNumAsistentesMax($datos->evento->num_asistentes_max);
        $evento->setTramo($datos->evento->tramo);
        $evento->setImagen($datos->evento->imagen);
        
        try{
            $em->persist($evento);
            $em->flush();
            return $this->json(['message'=>"Se ha podido modificar el evento ",
                        'Success'=>true],202);
        }catch(PDOException){
            return $this->json(['message'=>'No se ha podido modificar el evento','Success'=>false],404);
        }
    }

    
    #[Route("/postEvento", name:"post_evento", methods:"POST")]
    
    public function new(Request $request,EntityManagerInterface $em,TramoRepository $repoT,JuegoRepository $repoJ,EventoRepository $repoE,SluggerInterface $slugger,UserRepository $repoU): Response
    {
 
        $datos=json_decode($request->getContent());

        $imagen=$repoJ->find($datos->evento->juego);
        $evento = new Evento();
        $evento->setNombre($datos->evento->nombre);
        $evento->setFecha(new DateTime($datos->evento->fecha));
        $evento->setNumAsistentesMax($datos->evento->num_asistentes_max);
        $evento->setTramo($repoT->find($datos->evento->tramo));
        $evento->setImagen($imagen->getImagen());
 
        $em->persist($evento);
        $em->flush();

        $presentacion= new Presentacion();
        $presentacion->setEvento($repoE->find($evento->getId()));
        $presentacion->setJuego($repoJ->find($datos->evento->juego));

        $em->persist($presentacion);
        $em->flush($presentacion);


        foreach($datos->evento->users as $user){
            $invitacion=new Invitacion(); 
            $invitacion->setEvento($repoE->find($evento->getId()));
            $invitacion->setUser($repoU->find($user));
            $em->persist($invitacion);
            $em->flush($invitacion);
        }
 
        return $this->json(['id'=> $evento->getId(),'Success'=>true], 202);
    }
}
