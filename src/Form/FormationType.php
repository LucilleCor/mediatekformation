<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', null, [
                'widget' => 'choice',
                'label' => 'Publié à ',
                'required' => true,
                'data' => isset($options['data']) &&
                    $options['data']->getPublishedAt() != null ? 
                    $options['data']->getPublishedAt() : new DateTime('now'),
                'constraints' => [
                    new LessThanOrEqual([
                    'value' => 'today',
                    'message' => 'La date ne peut pas être postérieure à aujourd\'hui.',
                    ]),
                ]
            ])
            ->add('title', null, [
                'label' => 'Titre ',
                'required' => true,
            ])
            ->add('description', null, [
                'label' => 'Description ',
                'required' => false
            ])
            ->add('videoId', null, [
                'label' => 'Id de la vidéo ',
                'required' => true,
            ])
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'id',
                'required' => true,
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'id',
                'multiple' => true,
                'required' => false,
                'label' => 'Catégories'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
