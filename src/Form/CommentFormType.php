<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add(
        'content',
        TextareaType::class,
        [
          'label' => false,
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a comment',
            ]),
            new Length([
              'maxMessage' => 'Your comment should be max {{ limit }} characters',
              'max' => 1000
            ]),
          ],
        ],

      )
      ->add('save', SubmitType::class, [
        'label' => 'Leave a comment',
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Comment::class,
    ]);
  }
}
