<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use App\Entity\Anonce;

class PdpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('brochure', FileType::class, [
            'label' => 'Selectionnez votre plus belle photo de couverture pour cette annonce',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => true,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '4024k',
                    'mimeTypes' => [
                        'image/jpg',
                        'image/JPG',
                        'image/jpeg',
                        'image/JPEG',
                        'image/PNG',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'choisissez une photo valide',
                ])
            ],
        ])
        ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Anonce::class,
        ]);
    }
}
