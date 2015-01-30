<?php

namespace Dende\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ColorType extends AbstractType
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
                'name',
                'text',
                [
                    "required" => true,
                    "constraints" => [],
                    "label" => 'car.form.label.add_color.name'
                ]
            )
            ->add(
                'hex',
                'text',
                [
                    "required" => true,
                    "constraints" => [],
                    "label" => 'car.form.label.add_color.hex',
                ]
            )
        ;
    }

    public function getName()
    {
        return 'dende_form_color';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dende\FrontBundle\Entity\Color',
            'csrf_protection' => false,
        ));
    }
}
