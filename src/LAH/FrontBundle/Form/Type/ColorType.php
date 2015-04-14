<?php
namespace LAH\FrontBundle\Form\Type;

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
                'a2lix_translations_gedmo',
                [
                    'translatable_class' => 'LAH\\FrontBundle\\Entity\\Color',
                    'fields'             => [
                            'name' => [
                                'label' => 'car.form.label.add_color.name',
                            ],
                        ],
                    'label' => ' ',
                ]
            );
    }

    public function getName()
    {
        return 'lah_form_color';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'LAH\FrontBundle\Entity\Color',
            'csrf_protection' => false,
        ]);
    }
}
