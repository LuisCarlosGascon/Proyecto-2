<?php

namespace App\Controller\Admin;

use App\Entity\Evento;
use App\Entity\Reserva;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Juego;

class DashboardController extends AbstractDashboardController
{
    // #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $valido=false;
        if($this->getUser()==null){
            $valido=false;
        }else{
            foreach($this->getUser()->getRoles() as $rol){
                if($rol=="ROLE_SUPER_ADMIN"){
                    $valido=true;
                }
            }
        }
        if(!$valido){
            return $this->redirectToRoute('index');
        }
        return $this->render('admin/index.html.twig');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Proyecto 2');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-user-secret');
        yield MenuItem::subMenu('Mantenimiento','fa fa-gears')->setSubItems([
             MenuItem::linkToCrud('Juegos','fa fa-gamepad',Juego::class),
             MenuItem::linkToCrud('Eventos','fa fa-address-book',Evento::class),
             MenuItem::linkToCrud('Reservas','fa fa-bookmark',Reserva::class),
             MenuItem::linkToCrud('Usuarios','fa fa-user-circle',User::class),
        ]);
    }
}
