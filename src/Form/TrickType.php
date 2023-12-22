<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use PictureType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('trickGroup', EntityType::class, [
                'class' => TrickGroup::class,
                'choice_label' => 'name',
                'label' => 'Choose a group trick : ',

            ])

            // ->add('videos', TextType::class)
            ->add('images', FileType::class, [
                'label' => 'Pictures for the trick',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoFormType::class,
                'label' => 'videos',
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false

            ])
            ->add(
                'save',
                SubmitType::class,
                [
                    'attr' => [
                        'clas' => 'btn btn-submit'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
