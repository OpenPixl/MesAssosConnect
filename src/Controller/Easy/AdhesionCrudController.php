<?php

namespace App\Controller\Easy;

use App\Entity\Gestion\adhesions\Adherent;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdhesionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adherent::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            AssociationField::new('saison', 'Saison')
                ->setCrudController(SaisonCrudController::class)
                ->setSortProperty('name'),
            AssociationField::new('member', 'membre')->autocomplete(),
            AssociationField::new('asso', 'Association')->autocomplete(),
            AssociationField::new('typeAdhesion', 'Type')->autocomplete(),
            MoneyField::new('cotisation')->setCurrency('EUR'),
            BooleanField::new('isPaid', 'Cotisation réglée')->hideOnForm(),
            BooleanField::new('isFree', 'Cotisation gratuite')->hideOnForm(),
            ChoiceField::new('paidBy', "Réglée par'")
                ->setChoices([
                    'Espèces' => 'especes',
                    'Chèque' => 'chèque',
                    'Carte Bleu'=> 'carte_bleu',
                    'Virement' => 'virement'
                ])
                ->setColumns(2)
            ,
            TextField::new('refPaid', 'Référence du paiement')
        ];
    }

}
