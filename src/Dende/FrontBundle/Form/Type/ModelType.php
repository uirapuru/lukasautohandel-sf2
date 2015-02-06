<?php

namespace Dende\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ModelType extends AbstractType
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
                    "constraints" => [
                        new Callback(function ($data, ExecutionContextInterface $context) {
                            $form = $context->getRoot();

                            if (!$form["add_model"]["brand"]->isEmpty() && $form["add_model"]["name"]->isEmpty()) {
                                $context->buildViolation('validator.you_have_to_add_car_model_name')
                                    ->atPath('add_model.name')
                                    ->addViolation();
                            }
                        }),
                    ],
                    "label" => 'car.form.label.add_model.name',
                ]
            )
            ->add(
                'brand',
                'text',
                [
                    "required" => true,
                    "constraints" => [
                        new Callback(function ($data, ExecutionContextInterface $context) {
                            $form = $context->getRoot();

                            if (!$form["add_model"]["name"]->isEmpty() && $form["add_model"]["brand"]->isEmpty()) {
                                $context->buildViolation('validator.you_have_to_add_car_brand')
                                    ->atPath('model')
                                    ->addViolation();
                            }
                        }),
                    ],
                    "label" => 'car.form.label.add_model.brand',
                    "mapped" => false,
                ]
            )
        ;
    }

    public function getName()
    {
        return 'dende_form_model';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dende\FrontBundle\Entity\Model',
            'csrf_protection' => false,
        ));
    }
}
