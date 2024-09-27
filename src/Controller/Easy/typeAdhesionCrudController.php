<?php

namespace App\Controller\Easy;

use App\Entity\Gestion\typeAdhesion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class typeAdhesionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return typeAdhesion::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('name'),
            TextEditorField::new('notes'),
        ];
    }

}
