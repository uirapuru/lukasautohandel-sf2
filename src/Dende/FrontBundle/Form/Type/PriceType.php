<?php

namespace Dende\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

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
                    'required' => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_enter_price_amount']),
                        new Range(['min' => '1', 'minMessage' => 'validator.minimal_price_must_be_over_0']),
                    ],
                    'label' => 'car.form.label.amount',
                    'error_bubbling' => false,
                    'attr' => [
                        'class' => 'col-sm-10',
                    ],
                ]
            )
            ->add(
                'currency',
                'entity',
                [
                    'class' => "Dende\FrontBundle\Entity\Currency",
                    'property' => 'symbol',
                    'required' => true,
                    'constraints' => [
                        new NotNull(),
                    ],
                    'label' => 'car.form.label.currency',
                    'error_bubbling' => false,
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
        $resolver->setDefaults([
            'data_class' => 'Dende\FrontBundle\Entity\Price',
            'csrf_protection' => false,
            'error_bubbling' => false,
            'attr' => [
                'collection_type' => 'price',
            ],
        ]);
    }
}
