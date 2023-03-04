<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use DateTime;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class UserController extends AbstractController
{
    #[Route('/misReservas/{id}', name: 'app_user_reservas')]
    public function index(ReservaRepository $repo,int $id,UserRepository $repoU): Response
    {
        if($this->getUser()==null || $this->getUser()->getId()!=$id){
            return $this->redirectToRoute('index');
        }
        $user=$repoU->find($id);
        $reservas=$repo->findBy(array("user"=>$user));

        return $this->render('user/reservas.html.twig',[
            'reservas'=>$reservas
        ]);
    }

    #[Route('/cancelarMiReserva/{id}', name: 'reserva_cancelar')]
    public function eliminar(ReservaRepository $repo,EntityManagerInterface $em,int $id): Response
    {
        if($this->getUser()==null || $this->getUser()->getId()!=$id){
            return $this->redirectToRoute('index');
        }
        $reserva=$repo->find($id);
        $reserva->setFCancelacion(new DateTime(date('Y-m-d')));
        $em->persist($reserva);
        $em->flush();

        return $this->redirect('misReservas/'.$this->getUser()->getId());
    }

    #[Route('/miPerfil/{id}', name: 'perfil')]
    public function perfil(EntityManagerInterface $em,int $id,Request $request,SluggerInterface $slugger,UserPasswordHasherInterface $passwordHasher,UserRepository $repo): Response
    {
        if($this->getUser()==null || $this->getUser()->getId()!=$id){
            return $this->redirectToRoute('index');
        }

        $user = $repo->find($id);
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->isValid()) {
            
            $datos=$form->getData();
          
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

                $user->setImagen($nuevoFile);

            }
            $user->setNombre($datos->getNombre());
            $user->setApe1($datos->getApe1());
            $user->setApe2($datos->getApe2());
            $user->setEmail($datos->getEmail());
            $user->setTelefono($datos->getTelefono());
            $user->setTelegram($datos->getTelegram());
            $user->setPassword($passwordHasher->hashPassword(
                $user,
                $datos->getPassword()));
            $em->persist($user);
            $em->flush();
            // $html=$this->renderView('principal/index.html.twig');
            // $pdfCreado=$pdf->generatePdf($html);
            // return new Response($pdfCreado);

        }

        return $this->render('user/perfil.html.twig',[
            'form'=>$form
        ]);
    }
}
