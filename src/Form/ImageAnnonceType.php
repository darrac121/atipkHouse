<?php

namespace App\Form;

use App\Entity\ImageAnnonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class ImageAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lien',FileType::class)
            /*
            ->add('lien', FileType::class, [
                'label' => 'img',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [new File(['maxSize' => '1024k','mimeTypes' => ['image/*',],'mimeTypesMessage' => 'Please upload a valid PDF document',])],]
            )*/
            ->add('status')
            ->add('idAnnonce')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImageAnnonce::class,
        ]);
    }
}
