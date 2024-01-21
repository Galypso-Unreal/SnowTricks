<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a trick name',
                    ]),
                    new Length([
                        'maxMessage' => 'Your trick name should be max {{ limit }} characters',
                        'max' => 200
                    ]),
                ],
            ])
            ->add('description', TextareaType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a trick description',
                    ]),
                    new Length([
                        'maxMessage' => 'Your trick description should be max {{ limit }} characters',
                        'max' => 5000
                    ]),
                ],
            ])
            ->add('trickGroup', EntityType::class, [
                'class' => TrickGroup::class,
                'choice_label' => 'name',
                'label' => 'Choose a group trick : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please choose a trick group',
                    ]),
                ],

            ])

            // ->add('videos', TextType::class)
            ->add('images', FileType::class, [
                'label' => 'Pictures for the trick',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints'=>[
                    new All(
                        new File([
                            'maxSize' => '5M',
                            'extensions' => [
                                'jpeg',
                                'png',
                                'jpg'
                            ],
                            'extensionsMessage' => 'Please upload a valid file (jpg / png / jpeg',
                        ]),
                    )
                    
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Pictures modify for the trick',
                'mapped' => false,
                'required' => false,
                'constraints'=>[
                    new All(
                        new File([
                            'maxSize' => '5M',
                            'extensions' => [
                                'jpeg',
                                'png',
                                'jpg'
                            ],
                            'extensionsMessage' => 'Please upload a valid file (jpg / png / jpeg',
                        ]),
                    )
                    
                ],
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
