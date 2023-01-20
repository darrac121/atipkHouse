<?php

namespace App\Form;

use App\Entity\LebelleOptionAnnonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LebelleOptionAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value',TextType::class,['attr' => ['class' => 'form-control '],'label' => 'Nom du champs personaliser'])
            ->add('status',TextType::class,['attr' => ['class' => 'form-control '],'label' => '1 obligatoire 0 non obligatoire'])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LebelleOptionAnnonce::class,
        ]);
    }
}
