<?php

namespace Dende\FrontBundle\Form\Type;

use Dende\FrontBundle\Dictionary\Country;
use Dende\FrontBundle\Dictionary\Engine;
use Dende\FrontBundle\Dictionary\Fuel;
use Dende\FrontBundle\Dictionary\Gearbox;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

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

            ->add(
                'file',
                'file',
                [
                    "required" => false,
                    "constraints" => [
                        new Image([])
                    ]
                ]
            );
    }

    public function getName()
    {
        return 'dende_form_add_image';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dende\FrontBundle\Entity\Image',
            'csrf_protection' => false
        ));
    }
}
