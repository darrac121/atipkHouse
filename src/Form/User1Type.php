<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['attr' => ['class' => 'form-control'],'label' => 'Nom'])
            ->add('firstname',TextType::class,['attr' => ['class' => 'form-control'],'label' => 'Prenom'])
            ->add('email',TextType::class,['attr' => ['class' => 'form-control'],'label' => 'Mail'])
            ->add('password',TextType::class,['attr' => ['class' => 'form-control'],'label' => 'Mot de passe'])
            ->add('telephone',TextType::class,['attr' => ['class' => 'form-control'],'label' => 'Telephone'])
            ->add('adresse',TextType::class,['attr' => ['class' => 'form-control'],'label' => 'Adresse'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
