<?php

namespace App\Form;

use App\Entity\Correspondance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CorrespondanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('couleur', ChoiceType::class, [
                'choices' => [
                    'Blanc' => 'Blanc',
                    'Noir' => 'Noir',
                    'Beige' => 'Beige',
                    'Marron' => 'Marron',
                    'Gris' => 'Gris'
                ],
                'label' => 'Couleur dominante',
                'required' => false
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
                'required' => false
            ])
            ->add('caractere', ChoiceType::class, [
                'choices' => [
                    'Calme' => 'Calme',
                    'Joueur' => 'Joueur',
                    'Excité' => 'Excité'
                ],
                'label' => 'Caractère',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Correspondance::class,
        ]);
    }
}
