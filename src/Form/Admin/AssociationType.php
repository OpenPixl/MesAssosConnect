<?php

namespace App\Form\Admin;

use App\Entity\Admin\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom',
                'required' => false
            ])
            ->add('object', TextareaType::class, [
                'label' => "Objet de l'association",
                'required' => false
            ])
            ->add('isRna', CheckboxType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
            ])
            ->add('numRna')
            ->add('address',  TextType::class,[
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('bisAddress', TextType::class,[
                'label' => '',
                'required' => false
            ])
            ->add('zipcode', TextType::class,[
                'label' => 'CP',
                'required' => false
            ])
            ->add('city', TextType::class,[
                'label' => 'Commune',
                'required' => false
            ])
            ->add('contactPhone', TextType::class,[
                'label' => 'Numéro de téléphone',
                'required' => false
            ])
            ->add('contactEmail', TextType::class,[
                'label' => 'Email',
                'required' => true
            ])
            ->add('site', TextType::class,[
                'label' => 'Site Web',
                'required' => false
            ])
            ->add('linkFb', TextType::class,[
                'label' => 'Facebook',
                'required' => false
            ])
            ->add('linkInst', TextType::class,[
                'label' => 'Instagram',
                'required' => false
            ])
            ->add('linkGoo', TextType::class,[
                'label' => 'Google Business',
                'required' => false
            ])
            ->add('logoFile', FileType::class,[
                'label' => "Le fichier ne doit pas dépasser 10Mo de taille",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10000k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Attention, veuillez charger un fichier au format jpg ou png',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
