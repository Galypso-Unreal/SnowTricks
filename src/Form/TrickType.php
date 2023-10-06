<?php 

namespace App\Form;

use App\Entity\Trick;
use PictureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            // ->add('trickGroup', TextType::class)
            
            // ->add('videos', TextType::class)
            ->add('pictures', CollectionType::class, [
                // each entry in the array will be an "email" field
                'entry_type' => PictureType::class,
                // these options are passed to each "email" type
                'entry_options' => [
                    'attr' => ['class' => 'pictures-box'],
                ],
                'by_reference' => true,
                'allow_add' => true,
                'allow_delete' => true,
                ])
                ->add('Sauvegarder',SubmitType::class,[
                    'attr'=>[
                        'clas'=> 'btn btn-submit'
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