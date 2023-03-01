<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\Mailer;
use App\Service\PdfCreator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use PdfCreator as GlobalPdfCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\Telegram;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout() 
    {
        return $this->render('principal/index.html.twig');
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $em,Mailer $mailer,PdfCreator $pdf,SluggerInterface $slugger){
        // TODO - Hacer validaciones

        $user = new User();
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            
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
            $user->setPuntos(0);
            $em->persist($user);
            $em->flush();
            $mailer->sendEmail($user);
            // $html=$this->renderView('principal/index.html.twig');
            // $pdfCreado=$pdf->generatePdf($html);
            // return new Response($pdfCreado);
            
            //return $this->redirectToRoute('app_index');

        }
        return $this->render('security/register.html.twig',[
            'form'=>$form,
        ]);
    }
}
