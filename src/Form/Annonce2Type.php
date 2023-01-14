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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class Annonce2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            // ->add('titre',TextType::class,['data_class'=>'form-control'],['attr' => ['class' => 'form-control']])
            ->add('titre',TextType::class,['attr' => ['class' => 'form-control '],'label' => 'Nom'])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'multiple'=>true,
                'attr' => ['class' => 'form-control'],
                'label' => 'Selectioner une ou plusieurs photos',
            ])
            ->add('description',TextareaType::class,['attr' => ['class' => 'form-control']])
            ->add('adresse',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('Ville',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('CodePostal',TextType::class,['attr' => ['class' => 'form-control'],'label' => 'Code postal'])
            ->add('pays',TextType::class,['attr' => ['class' => 'form-control','value' => 'France'],'label' => 'Pays'])
            ->add('prix',TextType::class,['attr' => ['class' => 'form-control prix'],'label' => 'Prix'])
            ->add('nbmax',TextType::class,['attr' => ['class' => 'form-control'],'label' => 'Nombre de lit disponible'])
            ->add('datedebut',DateTimeType::class,['attr' => ['class' => 'form-control'],'label' => 'Date début de disponibilité du bien'])
            ->add('dateFin',DateTimeType::class,['attr' => ['class' => 'form-control'],'label' => 'Date début de disponibilité du bien'])
            ->add('datecreation',DateTimeType::class,['attr' => ['class' => 'invisible'],'label' =>' '])
            ->add('status',HiddenType::class,['data' => 1,'attr' => ['class' => 'invisible','value' => '1'],'label' =>' '])
            // ->add('lien',FileType::class)
            
            
            
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
