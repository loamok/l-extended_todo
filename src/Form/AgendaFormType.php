<?php

namespace App\Form;

use App\Entity\Agenda;
use App\Entity\AgType;
use App\Entity\Timezone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgendaFormType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name')
            ->add('type', EntityType::class, [
                'class' => AgType::class,
                'choice_label' => 'label',
            ])
            ->add('tz', EntityType::class, [
                'class' => Timezone::class,
                'choice_label' => 'name',
                'label' => 'Timezone',
            ])
            ->add('submit', SubmitType::class, ['label' => "Register"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Agenda::class,
        ]);
    }
}
