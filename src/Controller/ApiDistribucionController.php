<?php

namespace App\Controller;

use App\Entity\Distribucion;
use App\Entity\Distribuciones;
use App\Entity\Mesa;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DistribucionRepository;
use App\Repository\MesaRepository;
use App\Repository\DistribucionesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use PDOException;
use DateTime;


#[Route("/api", name:"api_")]
class ApiDistribucionController extends AbstractController
{
    #[Route('/getDistribuciones', name: 'get_distribuciones',methods:'GET')]
    public function findAll(DistribucionRepository $repo): JsonResponse
    {
        $distribuciones=$repo->findAll();

        $datos=[];

        foreach($distribuciones as $distribucion){
            $datos[]=[
                'id'=>$distribucion->getId(),
                'fecha'=>$distribucion->getFecha(),
            ];
        }

        return $this->json(['distribuciones'=>$datos,'Success'=>true],201);
    }

    #[Route("/deleteDistribucion/{id}", name:"delete_distribucion", methods:"DELETE")]
     
    public function delete(int $id,EntityManagerInterface $em): Response
    {
        
        $distribucion = $em->getRepository(Distribucion::class)->find($id);
 
        if (!$distribucion) {
            return $this->json(['message'=>'Distribucion no encontrada con el id: ' . $id,'Success'=>false], 404);
        }
 
        $em->remove($distribucion);
        $em->flush();
 
        return $this->json(['message'=>'Distribucion eliminada con exito con id: ' . $id,'Success'=>true], 202);
    }

    
    

    #[Route('/getMesasDistribuciones/{id}', name: 'get_mesa_distribuciones',methods:'GET')]
    public function getMesasDist(DistribucionRepository $repo,int $id): JsonResponse
    {
        $distribuciones=$repo->getMesasDistribucion($id);
        
        $datos=[];

        foreach($distribuciones as $distribucion){
            $datos[]=[
                'id'=>$distribucion->getId(),
                'fecha'=>$distribucion->getFecha(),
                'x'=>$distribucion->getX(),
                'y'=>$distribucion->getY(),
            ];
        }
        return $this->json(['distribuciones'=>$datos,'Success'=>true],201);
    }

    #[Route('/getIdMesaDistribucion/{id}', name: 'get_mesa_distribuciones',methods:'GET')]
    public function getIdMesaDistribucion(DistribucionesRepository $repo,int $id): JsonResponse
    {
        $distribuciones=$repo->findBy(array('mesa'=>$id));
        
        $datos=[];

        foreach($distribuciones as $distribucion){
            $datos[]=[
                'id'=>$distribucion->getId(),
                'mesa'=>$distribucion->getMesa(),
                'distribucion'=>$distribucion->getDistribucion(),
            ];
        }
        return $this->json(['distribuciones'=>$datos,'Success'=>true],201);
    }

    #[Route('/getMesaDistribucion/{id}/{idDist}', name: 'get_mesa_distribuciones',methods:'GET')]
    public function getMesaDistribucion(DistribucionesRepository $repo,int $id,int $idDist,DistribucionRepository $repo2): JsonResponse
    {
        $distribuciones=$repo->findBy(array('mesa'=>$id,'distribucion'=>$idDist));
        $datos=[];

        foreach($distribuciones as $distribucion){
            //$distribucionMesa=$repo2->find($distribucion->getDistribucion()->getId());
            $datos[]=[
                'id'=>$distribucion->getId(),
                'x'=>$distribucion->getX(),
                'y'=>$distribucion->getY(),

            ];
        }
        return $this->json(['distribuciones'=>$datos,'Success'=>true],201);
    }

    #[Route("/postDistribucion", name:"post_distribucion", methods:"POST")]
    
    public function createDistribucion(Request $request,DistribucionRepository $repo,EntityManagerInterface $em): Response
    {
        $datos=json_decode($request->getContent());
        $fecha=new DateTime($datos->distribucion->fecha);
        $dist=new Distribucion();

        $dist->setFecha($fecha);
        
        try{
            $em->persist($dist);
            $em->flush();
            return $this->json(['message'=>"Se ha podido crear la distribucion ",
                        'Success'=>true],202);
        }catch(PDOException){
            return $this->json(['message'=>'No se ha podido crear la distribucion','Success'=>false],404);
        } 
    }


    #[Route("/postDistribucionMesa", name:"post_distribucion_mesa", methods:"POST")]
    
    public function createDistribucionMesa(Request $request,DistribucionesRepository $repo,EntityManagerInterface $em,MesaRepository $repoMesa,DistribucionRepository $repoDist): Response
    {
        $datos=json_decode($request->getContent());
        $distMesa=new Distribuciones();
        $mesa=$repoMesa->find($datos->disposiciones->mesa);
        $distribucion=$repoDist->find($datos->disposiciones->distribucion);

        $distMesa->setMesa($mesa);
        $distMesa->setDistribucion($distribucion);
        $distMesa->setX($datos->disposiciones->x);
        $distMesa->setY($datos->disposiciones->y);
        
        try{
            $em->persist($distMesa);
            $em->flush();
            return $this->json(['message'=>"Se ha podido crear la distribucion ",
                        'Success'=>true],202);
        }catch(PDOException){
            return $this->json(['message'=>'No se ha podido crear la distribucion','Success'=>false],404);
        } 
    }

    #[Route("/putDistribucionMesa", name:"edit_distribucion", methods:"PUT")]
    
    public function edit(Request $request,DistribucionesRepository $repo,EntityManagerInterface $em): Response
    {
        $datos=json_decode($request->getContent());
        $distribucion=$repo->find($datos->disposiciones->id);
        
 
        if (!$distribucion) {
            return $this->json(['message'=>'Distribucion no encontrada','Success'=>false],404);
        }

        $distribucion->setX($datos->disposiciones->x);
        $distribucion->setY($datos->disposiciones->y);
        
        try{
            $em->persist($distribucion);
            $em->flush();
            return $this->json(['message'=>"Se ha podido modificar la distribucion ",
                        'Success'=>true],202);
        }catch(PDOException){
            return $this->json(['message'=>'No se ha podido modificar la mesa','Success'=>false],404);
        } 
    }
}
