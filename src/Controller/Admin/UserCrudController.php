<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('nombre'),
            TextField::new('ape1'),
            TextField::new('ape2'),
            ChoiceField::new('roles')
                ->setChoices([
                    'SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                    'ADMIN' => 'ROLE_ADMIN',
                    'USER' => 'ROLE_USER',
                ])
                ->allowMultipleChoices(),
            IntegerField::new('telefono'),
            TextField::new('email'),
            TextField::new('telegram'),
            TextField::new('password'),
            ImageField::new('imagen')
                ->setBasePath('public/img')
                ->setUploadDir('public/img')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]'),
        ];
    }
    
}
