<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JuegoRepository;
use App\Entity\Juego;
use App\Form\JuegoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;

class JuegoController extends AbstractController
{
    #[Route('/juegos', name: 'app_juegos')]
    public function index(JuegoRepository $repo): Response
    {
        $juegos=$repo->findAll();
        return $this->render('juego/lista.html.twig', [
            'juegos' => $juegos,
        ]);
    }

    #[Route('/juegoEditar/{id}', name: 'app_juego_editar')]
    public function editaJuego(JuegoRepository $repo,int $id,Request $request,SluggerInterface $slugger,EntityManagerInterface $em): Response
    {
        $valido=false;
        if($this->getUser()==null){
            $valido=false;
        }else{
            foreach($this->getUser()->getRoles() as $rol){
                if($rol=="ROLE_ADMIN"){
                    $valido=true;
                }
            }
        }
        if(!$valido){
            return $this->redirectToRoute('index');
        }

        $juego=$repo->find($id);
        $imagen=$juego->getImagen();

        $juegoNuevo=new Juego();

        $form=$this->createForm(JuegoType::class,$juego);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $juego = $form->getData();

            $file=$form->get('imagen')->getData();

            if($file){
                $nombreOriginal=pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $salvaFile=$slugger->slug($nombreOriginal);
                $nuevoFile=$salvaFile.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('brochures_directory'),
                        $nuevoFile
                    );
                } catch (FileException $e) {
                }

                $juego->setImagen($nuevoFile);

            }else{
                $juego->setImagen($imagen);
            }

            $em->persist($juego);
            $em->flush();

            return $this->redirectToRoute('app_juegos');
        }

        return $this->render('juego/editar.html.twig', [
            'juego' => $juego,
            'form' =>$form,
        ]);
    }

    #[Route('/juegoCrear', name: 'app_juego_crear')]
    public function creaJuego(JuegoRepository $repo,Request $request,SluggerInterface $slugger,EntityManagerInterface $em): Response
    {
        $valido=false;
        if($this->getUser()==null){
            $valido=false;
        }else{
            foreach($this->getUser()->getRoles() as $rol){
                if($rol=="ROLE_ADMIN"){
                    $valido=true;
                }
            }
        }
        if(!$valido){
            return $this->redirectToRoute('index');
        }

        $juegoNuevo=new Juego();

        $form=$this->createForm(JuegoType::class,$juegoNuevo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $juego = $form->getData();

            $file=$form->get('imagen')->getData();

            if($file){
                $nombreOriginal=pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $salvaFile=$slugger->slug($nombreOriginal);
                $nuevoFile=$salvaFile.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('brochures_directory'),
                        $nuevoFile
                    );
                } catch (FileException $e) {
                }

                $juego->setImagen($nuevoFile);

            }

            $em->persist($juego);
            $em->flush();

            return $this->redirectToRoute('app_juegos');
        }

        return $this->render('juego/crear.html.twig', [
            'form' =>$form,
        ]);
    }

    #[Route('/juegosMantenimiento', name: 'app_juegos_mantenimientos')]
    public function mantenimiento(JuegoRepository $repo): Response
    {
        $valido=false;
        if($this->getUser()==null){
            $valido=false;
        }else{
            foreach($this->getUser()->getRoles() as $rol){
                if($rol=="ROLE_ADMIN"){
                    $valido=true;
                }
            }
        }
        if(!$valido){
            return $this->redirectToRoute('index');
        }

        $juegos=$repo->findAll();
        return $this->render('juego/mantenimiento.html.twig', [
            'juegos' => $juegos,
        ]);
    }

    #[Route('/juegoEliminar/{id}', name: 'app_juego_eliminar')]
    public function delete(JuegoRepository $repo,int $id,EntityManagerInterface $em): Response
    {
        $valido=false;
        if($this->getUser()==null){
            $valido=false;
        }else{
            foreach($this->getUser()->getRoles() as $rol){
                if($rol=="ROLE_ADMIN"){
                    $valido=true;
                }
            }
        }
        if(!$valido){
            return $this->redirectToRoute('index');
        }
        
        $juego=$repo->find($id);

        $em->remove($juego);
        $em->flush();
        return $this->redirect('/juegosMantenimiento');
    }

    #[Route('/juegosDetalles', name: 'app_juegos_detalles')]
    public function detalles(JuegoRepository $repo): Response
    {
        $juego=$repo->find(2);
        return $this->render('juego/detalle.html.twig',[
            "juego"=>$juego,
        ]);
    }


    
}
