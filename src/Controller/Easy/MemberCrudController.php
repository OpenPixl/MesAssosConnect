<?php

namespace App\Controller\Easy;

use App\Entity\Admin\Member;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MemberCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Member::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Membre')
            ->setEntityLabelInPlural('Membres')
            ->setDateFormat('...')
            // ...
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Information du Membre'),
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('typeMember', "Rôle")
                ->setChoices([
                    'Administrateur' => 'administrateur',
                    'Membre' => 'membre',
                ])
                ->setColumns(2)
            ,
            EmailField::new('email', "Email"),
            ChoiceField::new('civility', "M/Mme")
                ->setChoices([
                    'M.' => 'M.',
                    'Mme' => 'Mme',
                ])
                ->setColumns(1)
                ,
            TextField::new("firstName", "Prénom")->setColumns(3),
            TextField::new("lastName", "Nom")->setColumns(3),
            FormField::addFieldset('Adresse')->collapsible(),
            TextField::new("address", "Rue")->hideOnIndex(),
            TextField::new("bisAddress", "")->hideOnIndex(),
            TextField::new("zipcode", "CP")
                ->hideOnIndex()
                ->setColumns(1),
            TextField::new("city", "City")
                ->hideOnIndex()
                ->setColumns(4),
            FormField::addFieldset(),

            BooleanField::new('isVerified', 'Mail vérifié ?')
                ->renderAsSwitch(true)
                ->hideOnform(),
            FormField::addTab('Autres'),
            DateTimeField::new('createAt')->hideOnForm(),
            DateTimeField::new('updateAt')->hideOnForm(),
        ];
    }

}
