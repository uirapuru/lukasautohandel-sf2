<?php
namespace LAH\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Image;

class ImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', [
                    'required'       => true,
                    'error_bubbling' => false,
                    'constraints'    => [
                        new Image([
                            'maxSize'                    => 10000000,
                            'maxSizeMessage'             => 'validator.maximum_file_size_reached',
                            'uploadFormSizeErrorMessage' => 'validator.file_is_too_large',
                        ]),
                    ],
            ])
            ->add('position', 'hidden', [
                'required'       => true,
                'error_bubbling' => false,
            ]);
    }

    public function getName()
    {
        return 'lah_image';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'LAH\MainBundle\Entity\Image',
            'csrf_protection' => false,
            'error_bubbling'  => false,
            'attr'            => [
                'collection_type' => 'image',
                'class'           => 'col-sm-10',
            ],
        ]);
    }
}
