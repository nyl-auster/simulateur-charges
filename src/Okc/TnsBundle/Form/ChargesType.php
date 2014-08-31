<?php

namespace Okc\TnsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChargesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('caHt', 'text')
          ->add('salaire', 'text')
          ->add('frais', 'text')
          ->add('cfe', 'text')
          ->add('cotisationsSocialesDejaVerseesEnN', 'text')
          ->add('cotisationRegime', 'choice',
            [
              'expanded' => FALSE,
              'multiple' => FALSE,
              'choices' => [
                'annee_1' => 'Premiere année',
                'annee_2' => 'Deuxieme année',
                'regime_etabli' => 'Régime établi'
              ]
            ])
          ->add('save', 'submit');
    }

    public function getName()
    {
        return 'charges';
    }
}