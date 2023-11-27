<?php

namespace App\Form;

use App\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType as FormFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // télécharger un fichier pas dans le dossier public
            ->add('file', FormFileType::class, [
                'label' => 'Fichier',
                // on ne mappe pas le fichier pour ne pas le stocker en bdd 
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-label mb-3',
                    // Ajoute d'autres attributs si nécessaire
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom du fichier',
                'attr' => [
                    'class' => 'form-control form-label  mb-3',
                    // Ajoute d'autres attributs si nécessaire
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => File::class,
        ]);
    }
}
