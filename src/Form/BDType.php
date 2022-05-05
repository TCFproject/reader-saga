<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\BD;
use App\Entity\Cathegorie;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BDType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('date_publication')
            ->add('FilePath', FileType::class)
            ->add('auteur', EntityType::class,[
                'class' => Auteur::class,
                'choice_label' => 'nom'
            ])
            ->add('categorie', EntityType::class,[
                'class' => Cathegorie::class,
                'choice_label' => 'libelle',
            ])
            ->add('genre', EntityType::class,[
                'class' => Genre::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
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
