<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class Annonce2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            // ->add('titre',TextType::class,['data_class'=>'form-control'],['attr' => ['class' => 'form-control']])
            ->add('titre',TextType::class,['attr' => ['class' => 'form-control '],'label' => 'Nom',])
            ->add('description',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('adresse',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('Ville',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('CodePostal',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('prix',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('datecreation',DateTimeType::class)
            ->add('status',TextType::class,['data' => 1,'attr' => ['class' => 'invisible'],'label' =>' '])
            // ->add('lien',FileType::class)
            //->add('idUser')
            ->add('nbmax')
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'multiple'=>true,
            ])
            
            
            // form-control
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
