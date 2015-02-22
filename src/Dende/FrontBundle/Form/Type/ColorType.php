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
                'translations',
                "a2lix_translations_gedmo",
                [
                    'translatable_class' => 'Dende\\FrontBundle\\Entity\\Color',
                    'fields' => [
                            'name' => [
                                'label' => 'car.form.label.add_color.name',
                            ],
                        ],
                    'label' => ' ',
                ]
            )
//            ->add(
//                'name',
//                'text',
//                [
//                    "required" => true,
//                    "constraints" => [],
//                    "label" => 'car.form.label.add_color.name',
//                ]
//            )
        ;
    }

    public function getName()
    {
        return 'dende_form_color';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Dende\FrontBundle\Entity\Color',
            'csrf_protection' => false,
        ]);
    }
}
