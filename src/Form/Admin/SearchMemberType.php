<?php

namespace App\Form\Admin;

use App\Form\Model\SearchMemberModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug', SearchType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un membre'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'data_class' => SearchMemberModel::class,
            'csrf_protection' => true
        ]);
    }
}
