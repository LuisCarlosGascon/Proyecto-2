<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MesaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Mesa;
use PDOException;

#[Route("/api", name:"api_")]
class ApiMesaController extends AbstractController
{
    #[Route('/getMesas', name: 'get_mesas',methods:'GET')]
    public function findAll(MesaRepository $repo): JsonResponse
    {
        $mesas=$repo->findAll();

        $datos=[];

        foreach($mesas as $mesa){
            $datos[]=[
                'id'=>$mesa->getId(),
                'alto'=>$mesa->getAlto(),
                'ancho'=>$mesa->getAncho(),
                'sillas'=>$mesa->getSillas(),
                'x'=>$mesa->getX(),
                'y'=>$mesa->getY(),
            ];
        }

        return $this->json(['mesas'=>$datos,'Success'=>true],201);
    }

    #[Route('/getMesa/{id}', name: 'get_mesa',methods:'GET')]
    public function find(MesaRepository $repo,int $id): JsonResponse
    {
        $mesa=$repo->find($id);
    

        if(!$mesa){
            return $this->json(['message'=>'Mesa no encontrada con el id ' . $id,'Success'=>false],404);
        }
        
        $dato=[
            "id"=>$mesa->getId(),
            "alto"=>$mesa->getAlto(),
            "ancho"=>$mesa->getAncho(),
            "sillas"=>$mesa->getSillas(),
            "x"=>$mesa->getX(),
            "y"=>$mesa->getY(),
        ];
        

        

        return $this->json(['mesa'=>$dato,'Success'=>true],201);
    }

    #[Route("/deleteMesa/{id}", name:"delete_mesa", methods:"DELETE")]
     
    public function delete(int $id,EntityManagerInterface $em): Response
    {
        
        $mesa = $em->getRepository(Mesa::class)->find($id);
 
        if (!$mesa) {
            return $this->json(['message'=>'Mesa no encontrada con el id: ' . $id,'Success'=>false], 404);
        }
 
        $em->remove($mesa);
        $em->flush();
 
        return $this->json(['message'=>'Mesa eliminada con exito con id: ' . $id,'Success'=>true], 202);
    }

    
    #[Route("/putMesa", name:"edit_mesa", methods:"PUT")]
    
    public function edit(Request $request,MesaRepository $repo,EntityManagerInterface $em): Response
    {
        $datos=json_decode($request->getContent());
        $mesa=$repo->find($datos->mesa->id);
        
 
        if (!$mesa) {
            return $this->json(['message'=>'Mesa no encontrada','Success'=>false],404);
        }
 
        $mesa->setAlto($datos->mesa->alto);
        $mesa->setAncho($datos->mesa->ancho);
        $mesa->setSillas($datos->mesa->sillas);
        $mesa->setX($datos->mesa->x);
        $mesa->setY($datos->mesa->y);
        
        try{
            $em->persist($mesa);
            $em->flush();
            return $this->json(['message'=>"Se ha podido modificar la mesa ",
                        'Success'=>true],202);
        }catch(PDOException){
            return $this->json(['message'=>'No se ha podido modificar la mesa','Success'=>false],404);
        }
    }

    
    #[Route("/postMesa", name:"post_mesa", methods:"POST")]
    
    public function new(Request $request,EntityManagerInterface $em): Response
    {
 
        $mesa = new Mesa();
        $mesa->setAlto($request->request->get('alto'));
        $mesa->setAncho($request->request->get('ancho'));
        $mesa->setSillas($request->request->get('sillas'));
        $mesa->setX($request->request->get('x'));
        $mesa->setY($request->request->get('y'));
 
        $em->persist($mesa);
        $em->flush();
 
        return $this->json(['message'=>'Mesa creada correctamente con el id ' . $mesa->getId(),'Success'=>true], 202);
    }
}
