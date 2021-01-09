<?php

namespace App\Form;

use App\Entity\DayParameters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DayParametersType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('amStart')
            ->add('amPauseStart')
            ->add('amPauseEnd')
            ->add('amPauseDuration', HiddenType::class, [])
            ->add('amEnd')
            ->add('amPmPauseDuration', HiddenType::class, [])
            ->add('pmStart')
            ->add('pmPauseStart')
            ->add('pmPauseEnd')
            ->add('pmPauseDuration', HiddenType::class, [])
            ->add('pmEnd')
            ->add('wtParameter', HiddenType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => DayParameters::class,
        ]);
    }
    
}
