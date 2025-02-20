<?php

namespace App\Form;

use App\Entity\BD;
use App\Entity\Cathegorie;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class BDType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('date_publication', DateType::class,[
                'data' => new \DateTime(),
                'format' => 'ddMMyyyy',
            ])
            ->add('categorie', EntityType::class,[
                'class' => Cathegorie::class,
                'choice_label' => 'libelle',
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('genre', EntityType::class,[
                'class' => Genre::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('FilePath', FileType::class,[
                'label' => 'Couverture',
                'mapped' => false,
                'constraints' => [
                    new Image()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BD::class,
        ]);
    }
}
