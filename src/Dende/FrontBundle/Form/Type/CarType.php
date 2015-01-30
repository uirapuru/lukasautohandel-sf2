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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CarType extends AbstractType
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
                    'empty_value' => 'car.form.choice.empty_car_type',
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
                    "label" => 'car.form.label.type'
                ]
            )
            ->add(
                'add_type',
                'dende_form_type',
                [
                    "label" => 'car.form.label.add_type',
                    "mapped" => false
                ]
            )
            ->add(
                'model',
                "entity",
                [
                    "class" => "Dende\FrontBundle\Entity\Model",
                    'empty_value' => 'car.form.choice.empty_car_model',
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
                    "label" => 'car.form.label.model'
                ]
            )
            ->add(
                'add_model',
                'dende_form_model',
                [
                    "label" => 'car.form.label.add_model',
                    "mapped" => false
                ]
            )
            ->add(
                'color',
                "entity",
                [
                    "class" => "Dende\FrontBundle\Entity\Color",
                    "property" => "name",
                    'empty_value' => 'car.form.choice.empty_car_color',
                    'empty_data' => null,
                    'required' => true,
                    'constraints' => [
                        new Callback(function ($data, ExecutionContextInterface $context) {
                            $form = $context->getRoot();

                            if ($form["add_color"]["name"]->isEmpty() && $form["color"]->isEmpty()) {
                                $context->buildViolation('validator.you_have_to_choose_car_color')
                                    ->atPath('color')
                                    ->addViolation();
                            }
                        }),
                    ],
                    "label" => 'car.form.label.color'
                ]
            )
            ->add(
                'add_color',
                'dende_form_color',
                [
                    "label" => 'car.form.label.add_color',
                    "mapped" => false
                ]
            )
            ->add(
                'year',
                "integer",
                [
                    "required" => true,
                    "constraints" => [
                        new NotNull(['message' => 'validator.you_have_to_enter_production_year']),
                        new Range([
                            "max" => (int) date("Y"),
                            "maxMessage" => "validator.production_year_too_big_",
                            "min" => 1900,
                            "minMessage" => "validator.production_year_too_small",
                        ]),
                        new Regex(["pattern" => "/\d\d\d\d/", "message" => "validator.production_year_format"]),
                    ],
                    "label" => 'car.form.label.year'
                ]
            )
            ->add(
                'distance',
                "integer",
                [
                    "required" => true,
                    "constraints" => [
                        new NotNull(['message' => 'validator.you_have_to_enter_distance']),
                        new Range([
                            "min" => 0,
                            "minMessage" => "validator.distance_too_small",
                        ]),
                    ],
                    "label" => 'car.form.label.distance'
                ]
            )
            ->add(
                'fuel',
                "choice",
                [
                    "choices" => Fuel::$choicesArray,
                    'empty_value' => 'car.form.choice.empty_car_fuel_type',
                    'empty_data' => null,
                    'required' => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_choose_car_fuel_type']),
                    ],
                    "label" => 'car.form.label.fuel'
                ]
            )
            ->add(
                'engine',
                "choice",
                [
                    "choices" => Engine::$choicesArray,
                    'empty_value' => 'car.form.choice.empty_car_engine_type',
                    'empty_data' => null,
                    'required' => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_choose_car_engine_type']),
                    ],
                    "label" => 'car.form.label.engine'
                ]
            )
            ->add(
                'gearbox',
                "choice",
                [
                    "choices" => Gearbox::$choicesArray,
                    'empty_value' => 'car.form.choice.empty_car_gearbox_type',
                    'empty_data' => null,
                    'required' => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_choose_car_gearbox_type']),
                    ],
                    "label" => 'car.form.label.gearbox'
                ]
            )
            ->add(
                'registrationCountry',
                "choice",
                [
                    "choices" => Country::$choicesArray,
                    'empty_value' => 'car.form.choice.empty_car_registration_country',
                    'empty_data' => null,
                    'required' => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_choose_car_registration_country']),
                    ],
                    "label" => 'car.form.label.registrationCountry'
                ]
            )
            ->add(
                'prices',
                "collection",
                [
                    "type" => new PriceType(),
                    "allow_add" => true,
                    "allow_delete" => true,
                    "by_reference" => false,
                    "prototype" => true,
                    "prototype_name" => "__name__",
                    "label" => 'car.form.label.prices'
                ]
            )
            ->add(
                'images',
                "collection",
                [
                    "type" => new ImageType(),
                    "allow_add" => true,
                    "allow_delete" => true,
                    "by_reference" => false,
                    "prototype" => true,
                    "prototype_name" => "__name__",
                    "label" => 'car.form.label.images'
                ]
            )
//            ->add(
//                'images',
//                "entity",
//                [
//                    "class" => "Dende\FrontBundle\Entity\Image",
//                    "multiple" => true,
//                    "expanded" => true
//                ]
//            )
            ->add(
                'promoteCarousel',
                "checkbox",
                [
                    "label" => 'car.form.label.promoteCarousel'
                ]
            )
            ->add(
                'promoteFrontpage',
                "checkbox",
                [
                    "label" => 'car.form.label.promoteFrontpage'
                ]
            )
            ->add(
                'title',
                "text",
                [
                    'required' => true,
                    'constraints' => [
                        new NotNull(["message" => "validator.title_cannot_be_empty"]),
                        new Length(["max" => 4096, "maxMessage" => "validator.title_max_length"]),
                    ],
                    "label" => 'car.form.label.title'
                ]
            )
            ->add(
                'description',
                "textarea",
                [
                    'required' => true,
                    'constraints' => [
                        new NotNull(["message" => "validator.description_cannot_be_empty"]),
                    ],
                    "label" => 'car.form.label.description'
                ]
            )
            ->add(
                'adminNotes',
                "textarea",
                [
                    "label" => 'car.form.label.adminNotes'
                ]
            )
            ->add(
                'hidden',
                "checkbox",
                [
                    "label" => 'car.form.label.hidden'
                ]
            )
        ;

//        $this->addEvents($builder);
    }

    public function getName()
    {
        return 'dende_form_car';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dende\FrontBundle\Entity\Car',
            'csrf_protection' => false,
        ));
    }

//    private function addEvents(FormBuilderInterface $builder)
//    {
//        $builder->addEventListener(
//            FormEvents::POST_SET_DATA,
//            function (FormEvent $event) {
//                $form = $event->getForm();
//                $data = $event->getData();
//
//                if ($form["add_model"]["name"]->getData()) {
//                    $constraints = [
//                        ,
//                    ];
//                } else {
//                    $constraints = [];
//                }
//
//                $form;
//            }
//        );
//
//    }
}
