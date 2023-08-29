<?php

namespace App\Form;

use App\Entity\Chien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('photo')
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
            ->add('description', TextType::class, [
                'label' => 'Texte',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chien::class,
        ]);
    }
}
