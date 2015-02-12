<?php

namespace Dende\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TitleType extends AbstractType
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
                'pl',
                'text',
                [
                    "required" => true,
                    "constraints" => [],
                    "label" => '_pl',
                    'mapped' => false,
                ]
            )->add(
                'en',
                'text',
                [
                    "required" => true,
                    "constraints" => [],
                    "label" => '_en',
                    'mapped' => false,
                ]
            )->add(
                'de',
                'text',
                [
                    "required" => true,
                    "constraints" => [],
                    "label" => '_de',
                    'mapped' => false,
                ]
            )
        ;
    }

    public function getName()
    {
        return 'dende_form_title';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dende\FrontBundle\Entity\Translation\CarTranslation',
            'csrf_protection' => false,
        ));
    }
}
