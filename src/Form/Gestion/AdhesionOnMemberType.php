<?php

namespace App\Form\Gestion;

use App\Entity\Admin\Association;
use App\Entity\Admin\Member;
use App\Entity\Gestion\Adhesion;
use App\Entity\Gestion\Saison;
use App\Entity\Gestion\typeAdhesion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdhesionOnMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('members', EntityType::class, [
                'class' => Member::class,
                'label' => "Membres liés par l'adhesion",
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.firstName', 'ASC');
                },
                'choice_label' => 'firstName',
                'choice_attr' => function (Member $member, $key, $index) {
                    return ['data-data' => $member->getFirstName()." ".$member->getLastName() ];
                }
            ])
            ->add('asso', EntityType::class, [
                'class' => Association::class,
                'label' => "Choix de l'association",
                'placeholder' => "Choisir l'association",
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
                'choice_attr' => function (Association $asso, $key, $index) {
                    return ['data-data' => $asso->getName() ];
                }
            ])

            ->add('typeAdhesion', EntityType::class, [
                'class' => typeAdhesion::class,
                'label' => "Choix de la cotisation",
                'placeholder' => "Veuillez d'abord choisir une association",
                'choices' => [],
                'choice_label' => 'name',
                'choice_attr' => function (typeAdhesion $tadhe, $key, $index) {
                    return ['data-data' => $tadhe->getName() ];
                }
            ])

            ->add('cotisation', MoneyType::class, [
                'label' => 'Tarif de la cotisation'
            ])
            ->add('isPaid')
            ->add('isFree')
            ->add('paidAt')
            ->add('paidBy',ChoiceType::class, [
                'label' => 'Réglée par',
                'choices'  => [
                    'Espèces' => 'Espèces',
                    "Carte" => 'Carte',
                    "Chèque" => 'Chèque',
                ],
            ])
            ->add('refPaid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adhesion::class,
        ]);
    }
}
