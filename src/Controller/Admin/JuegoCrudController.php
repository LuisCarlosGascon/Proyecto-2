<?php

namespace App\Controller\Admin;

use App\Entity\Juego;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class JuegoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Juego::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nombre'),
            TextField::new('descripcion'),
            IntegerField::new('min_jugadores'),
            IntegerField::new('max_jugadores'),
            IntegerField::new('alto'),
            IntegerField::new('ancho'),
            ImageField::new('imagen')->setBasePath('public/img')->setUploadDir('public/img'),
        ];
    }
    
}
