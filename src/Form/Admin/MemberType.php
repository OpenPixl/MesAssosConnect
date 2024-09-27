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

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roleMember', ChoiceType::class,[
                'label' => 'Rôle',
                'choices'  => [
                    'Administrateur' => "administrateur",
                    'Président.e' => 'president',
                    'Coprésident' => 'copresident',
                    'Secrétaire' => 'secretaire',
                    'Trésorier.e' => 'tresorier',
                    'Bénévole' => 'benevole',
                    'Salarié.e' => 'salarie',
                    'Adhérant' => "adhérant",
                ],
                'choice_attr' => [
                    'Administrateur' => ['data-data' => 'administrateur'],
                    'Adhérant' => ['data-data' => 'adherant'],
                ],
            ])
            ->add('typeMember', ChoiceType::class, [
                'label' => 'Type de membre',
                'attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => [
                    'Physique' => 1,
                    "Moral" => 2,
                ],
                'expanded' => true,
                'multiple' => false
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
                'required' => false
            ])
            ->add('lastName', TextType::class,[
                'label' => 'Nom',
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
                'required' => false
            ])
            ->add('bisAddress', TextType::class,[
                'label' => 'Complement',
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
            ->add('mobilePhone', TextType::class,[
                'label' => 'Mobile',
                'required' => false
            ])
            ->add('homePhone', TextType::class,[
                'label' => 'Fixe',
                'required' => false
            ])
            ->add('workPhone', TextType::class,[
                'label' => 'Travail',
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
