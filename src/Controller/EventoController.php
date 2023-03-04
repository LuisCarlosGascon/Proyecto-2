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
use App\Entity\Presentacion;
use App\Entity\Invitacion;


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
        $presentacion = $em->getRepository(Presentacion::class)->findBy(array("evento"=>$id));
        $invitacion = $em->getRepository(Invitacion::class)->findBy(array("evento"=>$id));

        return $this->render('evento/editar.html.twig', [
            'evento' => $evento,
            'tramo'=>$evento->getId(),
            'fecha'=>date_format($evento->getFecha(),'Y-m-d'),
            'presentacion'=>$presentacion,
            'invitacion'=>$invitacion
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

    #[Route('/eventoEliminar/{id}', name: 'app_evento_eliminar')]
    public function deleteEvento(EventoRepository $repo,int $id,EntityManagerInterface $em): Response
    { 
        $evento=$repo->find($id);
        $presentacion = $em->getRepository(Presentacion::class)->findBy(array("evento"=>$id));
        $invitacion = $em->getRepository(Invitacion::class)->findBy(array("evento"=>$id));

        foreach($presentacion as $p){
            $em->remove($p);
            $em->flush();
        }

        foreach($invitacion as $i){
            $em->remove($i);
            $em->flush();
        }

        $em->remove($evento);
        $em->flush();
        return $this->redirect('/evento');
    }
}
