<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('DateDebut',DateType::class,['attr' => ['class' => 'form-control date']])
        ->add('DateFin',DateType::class,['attr' => ['class' => 'form-control date']])
        ->add('NbNuit',IntegerType::class,['attr' => ['class' => 'form-control nb d-none','min' => 1,'value' => '0']])
        ->add('Total',IntegerType::class,['attr' => ['class' => '','min' => 1,'value' => '1']])
        ->add('Statue',HiddenType::class,['attr' => ['class' => 'd-none','value' => '0']])
        ->add('StatuePayment',HiddenType::class,['attr' => ['class' => 'disabled','min' => 0,'value' => '1']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}


