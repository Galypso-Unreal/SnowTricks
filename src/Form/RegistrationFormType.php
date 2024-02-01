<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{

  public function __construct(
    private UrlGeneratorInterface $router,
  ) {
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('username', TextType::class, [
        'constraints' => [
          new NotBlank([
            'message' => 'Please enter a username',
          ]),
          new Length([
            'maxMessage' => 'Your username should be max {{ limit }} characters',
            'max' => 180,
            'minMessage' => 'Your username should be at least {{ limit }} characters',
            'min' => 1
          ]),
        ],
      ])
      ->add('email', EmailType::class, [
        'constraints' => [
          new Email([
            'message' => 'Please enter a valid email',
          ]),
          new NotBlank([
            'message' => 'Please enter a email',
          ]),
          new Length([
            'maxMessage' => 'Your email should be max {{ limit }} characters',
            'max' => 255
          ]),
        ],
      ])
      ->add('agreeTerms', CheckboxType::class, [
        'mapped' => false,
        'required' => true,
        'label' => 'I agree with the <a target="_blank" href=' . $this->router->generate('app_legal') . '>terms of the site</a>',
        'label_html' => true,
        'constraints' => [
          new IsTrue([
            'message' => 'You should agree to our terms.',
          ]),
        ],
      ])
      ->add('plainPassword', PasswordType::class, [
        // instead of being set onto the object directly,
        // this is read and encoded in the controller
        'mapped' => false,
        'attr' => ['autocomplete' => 'new-password'],
        'constraints' => [
          new NotBlank([
            'message' => 'Please enter a password',
          ]),
          new Length([
            'min' => 12,
            'minMessage' => 'Your password should be at least {{ limit }} characters',
            'max' => 100,
            'maxMessage' => 'Your password should be not more {{ limit }} characters',
          ]),
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
