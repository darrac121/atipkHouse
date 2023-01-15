<?php

namespace App\Form;

use App\Entity\AvisAnnonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AvisAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('rating',IntegerType::class,['attr' => ['class' => 'form-control','value' => '1','max'=>'10','min'=>'1'],'label' => 'Donnee une notes'])
        ->add('message',TextareaType::class,['attr' => ['class' => 'form-control'],'label' => 'Donnee nous votre avis'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvisAnnonce::class,
        ]);
    }
}
