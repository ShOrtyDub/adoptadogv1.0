<?php

namespace App\Form;

use App\Entity\Chien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ChienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('age', NumberType::class, [
                'label' => 'Âge',
                'required' => true
            ])
            ->add('race', ChoiceType::class, [
                'choices' => [
                    'Labrador' => 'Labrador',
                    'Berger Allemand' => 'Berger Allemand',
                    'Golden Retriever' => 'Golden Retriever',
                    'French Bulldog' => 'French Bulldog',
                    'Bulldog' => 'Bulldog'
                ],
                'label' => 'Race',
                'required' => true
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'femelle' => 'Femelle',
                    'male' => 'Mâle'
                ],
                'label' => 'Sexe',
                'required' => true
            ])
            ->add('couleur', ChoiceType::class, [
                'choices' => [
                    'Blanc' => 'Blanc',
                    'Noir' => 'Noir',
                    'Beige' => 'Beige',
                    'Marron' => 'Marron',
                    'Gris' => 'Gris'
                ],
                'label' => 'Couleur dominante',
                'required' => true
            ])
            ->add('photo', FileType::class, [
                'data_class' => null,
                'empty_data' => '',
                'label' => 'Photo',
                'mapped' => true,
                'required' => false,
                'help' => 'Fichier jpg, jpeg,png ou webp ne dépassant pas 1Mo',
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'maxSizeMessage' => 'Votre fichier ne doit pas dépasser 1Mo',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => "This document isn't valid",
                    ]),
                ]
            ])
            ->add('taille', NumberType::class, [
                'label' => 'Taille en cm',
                'required' => true
            ])
            ->add('poids', NumberType::class, [
                'label' => 'Poids en kg',
                'required' => true
            ])
            ->add('caractere', ChoiceType::class, [
                'choices' => [
                    'Calme' => 'Calme',
                    'Joueur' => 'Joueur',
                    'Excité' => 'Excité'
                ],
                'label' => 'Caractère',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Texte',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chien::class,
        ]);
    }
}
