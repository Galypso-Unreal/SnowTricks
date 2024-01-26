<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class VideoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('embed', TextareaType::class,[
                'constraints' => [
                    new Length([
                        'maxMessage' => 'Your trick embed should be max {{ limit }} characters',
                        'max' => 1000
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
            'attr' => array(
                "class" => 'py-3'
            )
        ]);
    }
}
