<?php

namespace App\Form\Admin;

use App\Entity\Admin\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeMember', ChoiceType::class, [
                'label' => 'Type de membre',
                'choices'  => [
                    'physique' => 0,
                    "morale" => 1,
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
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Entrez votre Prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez votre Nom'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Adresse'
                ]
            ])
            ->add('bisAddress', TextType::class, [
                'label' => 'Complement',
                'attr' => [
                    'placeholder' => 'Complément'
                ]
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'CP',
                'attr' => [
                    'placeholder' => 'CP'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Commune',
                'attr' => [
                    'placeholder' => 'CP'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'help' => "Pour la création de votre mot de passe, ce dernier doit posséder au minimum 8 caractères, avoir au moins une majuscule, un chiffre et un caractère spécial (? , ! , _ , ect...)",
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Il nous faut un mot de passe, ne laissez pas ce champs Vide.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[A-Z]/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre majuscule.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[a-z]/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre minuscule.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[0-9]/',
                        'message' => 'Le mot de passe doit contenir au moins un chiffre.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[\W_]/',
                        'message' => 'Le mot de passe doit contenir au moins un caractère spécial.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => ['novalidate' => 'novalidate'], // Désactiver la validation HTML5
            'data_class' => Member::class,
        ]);
    }
}
