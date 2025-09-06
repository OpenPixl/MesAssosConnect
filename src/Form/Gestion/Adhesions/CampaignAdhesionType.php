<?php

namespace App\Form\Gestion\Adhesions;

use App\Entity\Gestion\Adhesions\Adherent;
use App\Entity\Gestion\Associations\CampaignAdhesion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignAdhesionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startAt', DateTimeType::class, [
                'label' => 'Ouverture de la campagne'
            ])
            ->add('finishAt', DateTimeType::class, [
                'label' => 'Fin de la campagne'
            ])
            ->add('name', TextType::class, [
                'label' => 'Titre de la campagne'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CampaignAdhesion::class,
        ]);
    }
}
