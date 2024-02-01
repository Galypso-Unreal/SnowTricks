<?php

use App\Entity\Picture;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class PictureType extends AbstractType
{
  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('name', FileType::class, array(
      'label' => 'Picture',
      'data_class' => null,
      'constraints' => [
        new Image([
          'maxSize' => '5M',
          'mimeTypes' => [
            'image/jpeg',
            'image/png',
            'image/jpg'
          ],
          'extensions' => [
            'jpeg',
            'png',
            'jpg'
          ],
          'extensionsMessage' => 'Please upload a valid file (jpg / png / jpeg',
        ]),
      ],
    ));
  }


  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Picture::class,
      'attr' => array(
        "class" => 'py-3'
      )
    ]);
  }
}
