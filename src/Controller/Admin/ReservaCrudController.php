<?php

namespace App\Controller\Admin;

use App\Entity\Reserva;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class ReservaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reserva::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield DateField::new('fecha');
        yield AssociationField::new('tramo');
        yield DateField::new('f_cancelacion');
        yield BooleanField::new('asiste');
        yield AssociationField::new('user');
        yield AssociationField::new('mesa');
    }
    
    public function createEntity(string $entityFqcn) {
        $entity = new Reserva();
        $entity->setAsiste(null);
        return $entity;
    }
}
