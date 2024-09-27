<?php

namespace App\Controller\Easy;

use App\Entity\Admin\Association;
use App\Form\gestion\AdhesionType;
use App\Form\gestion\typeAdhesionType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AssociationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Association::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Association')
            ->setEntityLabelInPlural('Associations')
            ->setDateFormat('...')
            // ...
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Informations générales'),
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            TextEditorField::new('object', 'Object'),
            BooleanField::new('isRna', "L'association possède un RNA ?")->renderAsSwitch(true),
            EmailField::new('contactEmail', 'Email de contact'),
            DateTimeField::new('createAt', 'Créer le')->hideOnForm(),
            DateTimeField::new('updateAt', 'mise à jour')->hideOnForm(),
            FormField::addTab('Membres'),
            CollectionField::new('adhesions')
                ->hideOnIndex()
                ->setEntryType(AdhesionType::class)
                ->allowAdd(true)
                ->allowDelete(true)
            ,
            FormField::addTab('Parametres'),
            CollectionField::new('typeAdhesions')
                ->hideOnIndex()
                ->setEntryType(typeAdhesionType::class)
                ->allowAdd(true)
                ->allowDelete(true)
            ,
        ];
    }
}
