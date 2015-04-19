<?php
namespace LAH\FrontBundle\Form\Type;

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
                    'required'    => true,
                    'constraints' => [],
                    'label'       => '_pl',
                    'mapped'      => false,
                ]
            )->add(
                'en',
                'text',
                [
                    'required'    => true,
                    'constraints' => [],
                    'label'       => '_en',
                    'mapped'      => false,
                ]
            )->add(
                'de',
                'text',
                [
                    'required'    => true,
                    'constraints' => [],
                    'label'       => '_de',
                    'mapped'      => false,
                ]
            );
    }

    public function getName()
    {
        return 'lah_form_title';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'LAH\FrontBundle\Entity\Translation\CarTranslation',
            'csrf_protection' => false,
        ]);
    }
}
