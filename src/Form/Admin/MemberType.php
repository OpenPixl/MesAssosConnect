<?php

namespace App\Form\Admin;

use App\Entity\Admin\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('typeMember', ChoiceType::class, [
                'label' => 'Type de membre',
                'choices'  => [
                    'Personne physique' => 0,
                    "Personne morale" => 1,
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 0
            ])
            ->add('civility', ChoiceType::class, [
                'label' => 'Civilité',
                'attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => [
                    'M.' => 'M.',
                    "Mme" => 'Mme',
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('firstName', TextType::class,[
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom'
                ],
                'required' => false
            ])
            ->add('lastName', TextType::class,[
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom'
                ],
                'required' => false
            ])
            ->add('birthday', DateType::class,[
                'label'=> "Date de naissance",
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'required' => false,
                'by_reference' => true,
            ])
            ->add('address', TextType::class,[
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Adresse'
                ],
                'required' => false
            ])
            ->add('bisAddress', TextType::class,[
                'label' => 'Complement',
                'attr' => [
                    'placeholder' => 'Complément'
                ],
                'required' => false
            ])
            ->add('zipcode', TextType::class,[
                'label' => 'CP',
                'attr' => [
                    'placeholder' => 'CP'
                ],
                'required' => false
            ])
            ->add('city', TextType::class,[
                'label' => 'Commune',
                'attr' => [
                    'placeholder' => 'Commune'
                ],
                'required' => false
            ])
            ->add('mobilePhone', TextType::class,[
                'label' => 'Contacts Téléphoniques',
                'attr' => [
                    'placeholder' => '06 xx xx xx xx'
                ],
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => '- Le téléphone portable est obligatoire.'
                    ]),
                ],
                'empty_data' => ''
            ])
            ->add('homePhone', TextType::class,[
                'label' => 'Fixe',
                'attr' => [
                    'placeholder' => '05 xx xx xx xx'
                ],
                'required' => false
            ])
            ->add('workPhone', TextType::class,[
                'label' => 'Travail',
                'attr' => [
                    'placeholder' => 'Travail'
                ],
                'required' => false
            ])
            ->add('isVerified', CheckboxType::class, [
                'label' => 'Adresse Email vérifiée',
                'required' => false,
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
