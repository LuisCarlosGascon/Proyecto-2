<?php

namespace App\Controller;

use App\Entity\Mesa;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class MesaController extends AbstractController
{

    #[Route("/mesasSala", name:"sala")]
    public function sala(Request $request,EntityManagerInterface $em): Response
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
        
        return $this->render('mesa/mantenimiento.html.twig');
    }

}
