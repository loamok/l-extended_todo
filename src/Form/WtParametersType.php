<?php

namespace App\Form;

use App\Entity\WtParameters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WtParametersType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('user', HiddenType::class, [])
            ->add('agenda', HiddenType::class, [])
            ->add('name')
            ->add('defaultConfig')
            ->add('active')
            ->add('global')
            ->add('baseLunchBreakDuration', HiddenType::class, [])
            ->add('extendedLunchBreakDuration', HiddenType::class, [])
            ->add('shortedLunchBreakDuration', HiddenType::class, [])
            ->add('baseWorkDayHoursDuration', HiddenType::class, [])
            ->add('extendedWorkDayHoursDuration', HiddenType::class, [])
            ->add('shortedWorkDayHoursDuration', HiddenType::class, [])
            ->add('baseTotalDayBreaksDuration', HiddenType::class, [])
            ->add('extendedTotalDayBreaksDuration', HiddenType::class, [])
            ->add('shortedTotalDayBreaksDuration', HiddenType::class, [])
            ->add('annualToilDaysNumber')
            ->add('annualHolidayDaysNumber')
            ->add('noWorkBefore')
            ->add('noWorkAfter')
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => WtParameters::class,
        ]);
    }
}
