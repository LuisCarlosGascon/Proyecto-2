<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\JuegoRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Juego;
use PDOException;

#[Route("/api", name:"api_")]
class ApiJuegoController extends AbstractController
{
    #[Route('/getJuegos', name: 'get_juegos',methods:'GET')]
    public function findAll(JuegoRepository $repo): JsonResponse
    {
        $juegos=$repo->findAll();

        $datos=[];

        foreach($juegos as $juego){
            $datos[]=[
                'id'=>$juego->getId(),
                'nombre'=>$juego->getNombre(),
                'min_jugadores'=>$juego->getMinJugadores(),
                'max_jugadores'=>$juego->getMaxJugadores(),
                'alto'=>$juego->getAlto(),
                'ancho'=>$juego->getAncho(),
                'descripcion'=>$juego->getDescripcion(),
                'imagen'=>$juego->getImagen(),
            ];
        }

        return $this->json(['juegos'=>$datos,'Success'=>true],201);
    }

    #[Route('/getJuego/{id}', name: 'get_juego',methods:'GET')]
    public function find(JuegoRepository $repo,int $id): JsonResponse
    {
        $juego=$repo->find($id);
    

        if(!$juego){
            return $this->json(['message'=>'Juego no encontrado con el id ' . $id,'Success'=>false],404);
        }
        
        $dato=[
            'id'=>$juego->getId(),
            'nombre'=>$juego->getNombre(),
            'min_jugadores'=>$juego->getMinJugadores(),
            'max_jugadores'=>$juego->getMaxJugadores(),
            'alto'=>$juego->getAlto(),
            'ancho'=>$juego->getAncho(),
            'descripcion'=>$juego->getDescripcion(),
            'imagen'=>$juego->getImagen(),
        ];
        

        

        return $this->json(['juego'=>$dato,'Success'=>true],201);
    }

   

    
    #[Route("/putJuego", name:"edit_juego", methods:"PUT")]
    
    public function edit(Request $request,JuegoRepository $repo,EntityManagerInterface $em): Response
    {
        $datos=json_decode($request->getContent());
        $juego=$repo->find($datos->juego->id);
        
 
        if (!$juego) {
            return $this->json(['message'=>'Juego no encontrado','Success'=>false],404);
        }
 
        $juego->setNombre($datos->juego->nombre);
        $juego->setDescripcion($datos->juego->descripcion);
        $juego->setMinJugadores($datos->juego->min_jugadores);
        $juego->setMaxJugadores($datos->juego->max_jugadores);
        $juego->setAlto($datos->juego->alto);
        $juego->setAncho($datos->juego->ancho);
        $juego->setImagen($datos->juego->imagen);
        
        try{
            $em->persist($juego);
            $em->flush();
            return $this->json(['message'=>"Se ha podido modificar el juego ",
                        'Success'=>true],202);
        }catch(PDOException){
            return $this->json(['message'=>'No se ha podido modificar el juego','Success'=>false],404);
        }
    }

    
    #[Route("/postJuego", name:"post_mesa", methods:"POST")]
    
    public function new(Request $request,EntityManagerInterface $em): Response
    {
 
        $juego = new Juego();
        $juego->setNombre($request->request->get('nombre'));
        $juego->setDescripcion($request->request->get('descripcion'));
        $juego->setMinJugadores($request->request->get('min_jugadores'));
        $juego->setMaxJugadores($request->request->get('max_jugadores'));
        $juego->setAlto($request->request->get('alto'));
        $juego->setAncho($request->request->get('ancho'));
        $juego->setImagen($request->request->get('imagen'));
 
        $em->persist($juego);
        $em->flush();
 
        return $this->json(['message'=>'Juego creado correctamente con el id ' . $juego->getId(),'Success'=>true], 202);
    }

    #[Route("/deleteJuego/{id}", name:"delete_juego", methods:"DELETE")]
    public function delete(int $id,EntityManagerInterface $em): Response
    {
        
        $juego = $em->getRepository(Juego::class)->find($id);
 
        if (!$juego) {
            return $this->json(['message'=>'Juego no encontrado con el id: ' . $id,'Success'=>false], 404);
        }
 
        $em->remove($juego);
        $em->flush();
 
        return $this->json(['message'=>'Juego eliminado con exito con id: ' . $id,'Success'=>true], 202);
    }
}
