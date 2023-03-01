<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventoRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EventoType;
use App\Repository\TramoRepository;


class EventoController extends AbstractController
{
    #[Route('/evento', name: 'app_evento')]
    public function index(EventoRepository $repo): Response
    {
        $eventos=$repo->findAll();
        return $this->render('evento/lista.html.twig',[
            "eventos"=>$eventos,
        ]);
    }

    #[Route('/eventoEditar/{id}', name: 'app_evento_editar')]
    public function editaEvento(EventoRepository $repo,int $id,Request $request,SluggerInterface $slugger,EntityManagerInterface $em,TramoRepository $repoT): Response
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

        $evento=$repo->find($id);
        $fecha=date_format($evento->getFecha(),'Y-m-d');
        $imagen=$evento->getImagen();

        $tramo=$repoT->findAll();

        $form=$this->createForm(EventoType::class,$evento);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $evento = $form->getData();

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

                $evento->setImagen($nuevoFile);

            }else{
                $evento->setImagen($imagen);
            }

            $em->persist($evento);
            $em->flush();

            return $this->redirectToRoute('app_evento');
        }

        return $this->render('evento/editar.html.twig', [
            'evento' => $evento,
            'fecha'=>$fecha,
            'tramos'=>$tramo
        ]);
    }

    #[Route('/eventoCrear', name: 'app_evento_crear')]
    public function crear(): Response
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
        
        return $this->render('evento/crear.html.twig');
    }
}
