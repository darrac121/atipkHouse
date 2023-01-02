<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('DateDebut',DateType::class,['attr' => ['class' => 'form-control date']])
        ->add('DateFin',DateType::class,['attr' => ['class' => 'form-control date']])
        ->add('NbNuit',IntegerType::class,['attr' => ['class' => 'form-control nb d-none','min' => 1,'value' => '0']])
        ->add('Total',IntegerType::class,['attr' => ['class' => '','min' => 1,'value' => '1']])
        ->add('Statue',IntegerType::class,['attr' => ['class' => 'd-none','value' => '0']])
        ->add('StatuePayment',IntegerType::class,['attr' => ['class' => 'disabled','min' => 1,'value' => '1']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
