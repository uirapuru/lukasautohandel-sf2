<?php

namespace Dende\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class PriceType extends AbstractType
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
                'amount',
                'integer',
                [
                    "required" => true,
                    "constraints" => [
                        new NotNull()
                    ],
                    "label" => 'car.form.label.amount'
                ]
            )
            ->add(
                'currency',
                'entity',
                [
                    "class" => "Dende\FrontBundle\Entity\Currency",
                    "property" => "symbol",
                    "required" => true,
                    "constraints" => [
                        new NotNull()
                    ],
                    "label" => 'car.form.label.currency'
                ]
            )
        ;
    }

    public function getName()
    {
        return 'dende_form_price';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dende\FrontBundle\Entity\Price',
            'csrf_protection' => false
        ));
    }
}
