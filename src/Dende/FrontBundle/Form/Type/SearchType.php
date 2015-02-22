<?php

namespace Dende\FrontBundle\Form\Type;

use Dende\FrontBundle\Dictionary\Country;
use Dende\FrontBundle\Dictionary\Engine;
use Dende\FrontBundle\Dictionary\Fuel;
use Dende\FrontBundle\Dictionary\Gearbox;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SearchType extends AbstractType
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
                'type',
                "entity",
                [
                    "class" => "Dende\FrontBundle\Entity\Type",
                    "property" => "name",
                    'empty_value' => 'car.form.choice.all_types',
                    'empty_data' => null,
                    'required' => true,
                    'constraints' => [
                        new Callback(function ($data, ExecutionContextInterface $context) {
                            $form = $context->getRoot();

                            if ($form["add_type"]["name"]->isEmpty() && $form["type"]->isEmpty()) {
                                $context->buildViolation('validator.you_have_to_choose_car_type')
                                    ->atPath('type')
                                    ->addViolation();
                            }
                        }),
                    ],
                    "label" => 'car.form.label.type',
                ]
            )
            ->add(
                'brand',
                "entity",
                [
                    "class" => "Dende\FrontBundle\Entity\Brand",
                    'empty_value' => 'car.form.choice.all_brands',
                    'empty_data' => null,
                    "property" => 'getName',
                    'required' => true,
                    'constraints' => [
                        new Callback(function ($data, ExecutionContextInterface $context) {
                            $form = $context->getRoot();

                            if ($form["add_model"]["name"]->isEmpty() && $form["model"]->isEmpty()) {
                                $context->buildViolation('validator.you_have_to_choose_car_model')
                                    ->atPath('model')
                                    ->addViolation();
                            }
                        }),
                    ],
                    "label" => 'car.form.label.model',
                ]
            )
            ->add(
                'model',
                "entity",
                [
                    "class" => "Dende\FrontBundle\Entity\Model",
                    'empty_value' => 'car.form.choice.all_models',
                    'empty_data' => null,
                    "property" => 'getFullName',
                    'required' => true,
                    'constraints' => [
                        new Callback(function ($data, ExecutionContextInterface $context) {
                            $form = $context->getRoot();

                            if ($form["add_model"]["name"]->isEmpty() && $form["model"]->isEmpty()) {
                                $context->buildViolation('validator.you_have_to_choose_car_model')
                                    ->atPath('model')
                                    ->addViolation();
                            }
                        }),
                    ],
                    "label" => 'car.form.label.model',
                ]
            )
        ;
    }

    public function getName()
    {
        return 'dende_form_search';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => true,
        ]);
    }
}
