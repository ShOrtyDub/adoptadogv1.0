<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true
            ])
//            ->add('roles', ChoiceType::class, [
//                'choices' => [
//                    'Administrateur' => 'ROLE_ADMIN',
//                    'Visiteur' => 'ROLE_VISITOR',
//                ],
//                'label' => 'Role',
//                'required' => true
//            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Mot de passe ne correspond pas',
                'options' => ['attr' => ['class' => 'password-field', 'autocomplete' => 'new-password']],
                'required' => is_null($builder->getData()->getId()),
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe']
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => true
            ]);

//        $builder->get('roles')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($rolesArray) {
//                    return count($rolesArray) ? $rolesArray[0] : null;
//                },
//                function ($rolesString) {
//                    return [$rolesString];
//                }
//            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
