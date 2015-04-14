<?php
namespace LAH\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TypeType extends AbstractType
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
                    'required'    => true,
                    'constraints' => [],
                    'label'       => 'car.form.label.add_type.name',
                ]
            );
    }

    public function getName()
    {
        return 'lah_form_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'LAH\FrontBundle\Entity\Type',
            'csrf_protection' => false,
        ]);
    }
}
