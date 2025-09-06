<?php

namespace App\Form\Gestion\Adhesions;

use App\Entity\Gestion\Adhesions\Adhesion;
use App\Entity\Gestion\Adhesions\Cotisation;
use App\Entity\Gestion\Associations\Association;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class AdhesionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Association|null $association */
        $association = $options['association'];

        $builder
            ->add('cotisation', EntityType::class, [
                'label' => 'Choix de la cotisation',
                'class' => Cotisation::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($association) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.association = :association')
                        ->setParameter('association', $association);
                },
            ])
            ->add('priceCotisation', moneyType::class,[
                'label' => 'Montant de la cotisation',
                'required' => false,
            ])
            ->add('payBy')
            ->add('payAt')
            ->add('startAt')
            ->add('finishAt')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adhesion::class,
            'association' => null,
        ]);
    }
}
