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

class CarType extends AbstractType
{
    /**
     * @var array
     */
    private $languages = [];

    /**
     * @var string
     */
    private $defaultLocale = 'pl';

    /**
     * @param array $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @param string $defaultLocale
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $languages = $this->languages;

        $builder
            ->add(
                'type',
                'entity',
                [
                    'class'       => "Dende\FrontBundle\Entity\Type",
                    'property'    => 'name',
                    'empty_value' => 'car.form.choice.empty_car_type',
                    'empty_data'  => null,
                    'required'    => true,
                    'constraints' => [
                        new Callback(function ($data, ExecutionContextInterface $context) {
                            $form = $context->getRoot();

                            if ($form['add_type']['name']->isEmpty() && $form['type']->isEmpty()) {
                                $context->buildViolation('validator.you_have_to_choose_car_type')
                                    ->atPath('type')
                                    ->addViolation();
                            }
                        }),
                    ],
                    'label' => 'car.form.label.type',
                ]
            )
            ->add(
                'add_type',
                'dende_form_type',
                [
                    'label'  => 'car.form.label.add_type',
                    'mapped' => false,
                ]
            )
            ->add(
                'model',
                'entity',
                [
                    'class'       => "Dende\FrontBundle\Entity\Model",
                    'empty_value' => 'car.form.choice.empty_car_model',
                    'empty_data'  => null,
                    'property'    => 'getFullName',
                    'required'    => true,
                    'constraints' => [
                        new Callback(function ($data, ExecutionContextInterface $context) {
                            $form = $context->getRoot();

                            if ($form['add_model']['name']->isEmpty() && $form['model']->isEmpty()) {
                                $context->buildViolation('validator.you_have_to_choose_car_model')
                                    ->atPath('model')
                                    ->addViolation();
                            }
                        }),
                    ],
                    'label' => 'car.form.label.model',
                ]
            )
            ->add(
                'add_model',
                'dende_form_model',
                [
                    'label'  => 'car.form.label.add_model',
                    'mapped' => false,
                ]
            )
            ->add(
                'color',
                'entity',
                [
                    'class'       => "Dende\FrontBundle\Entity\Color",
                    'empty_value' => 'car.form.choice.empty_car_color',
                    'empty_data'  => null,
                    'property'    => 'getFullName',
                    'required'    => true,
                    'constraints' => [
                        new Callback(function ($data, ExecutionContextInterface $context) use ($languages) {
                            $form = $context->getRoot();

                            $translationEmpty = false;

                            foreach ($languages as $language) {
                                if ($form['add_color']['translations'][$language]['name']->isEmpty()) {
                                    $translationEmpty = true;
                                    break;
                                }
                            }

                            if ($translationEmpty && $form['color']->isEmpty()) {
                                $context->buildViolation('validator.you_have_to_choose_car_color')
                                    ->atPath('color')
                                    ->addViolation();
                            }
                        }),
                    ],
                    'label' => 'car.form.label.color',
                ]
            )
            ->add(
                'add_color',
                'dende_form_color',
                [
                    'label'  => 'car.form.label.add_color',
                    'mapped' => false,
                ]
            )
            ->add(
                'year',
                'integer',
                [
                    'required'    => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_enter_production_year']),
                        new Range([
                            'max'        => (int) date('Y'),
                            'maxMessage' => 'validator.production_year_too_big_',
                            'min'        => 1900,
                            'minMessage' => 'validator.production_year_too_small',
                        ]),
                        new Regex(['pattern' => "/\d\d\d\d/", 'message' => 'validator.production_year_format']),
                    ],
                    'label' => 'car.form.label.year',
                ]
            )
            ->add(
                'distance',
                'integer',
                [
                    'required'    => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_enter_distance']),
                        new Range([
                            'min'        => 0,
                            'minMessage' => 'validator.distance_too_small',
                        ]),
                    ],
                    'label' => 'car.form.label.distance',
                ]
            )
            ->add(
                'fuel',
                'choice',
                [
                    'choices'     => Fuel::$choicesArray,
                    'empty_value' => 'car.form.choice.empty_car_fuel_type',
                    'empty_data'  => null,
                    'required'    => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_choose_car_fuel_type']),
                    ],
                    'label' => 'car.form.label.fuel',
                ]
            )
            ->add(
                'engine',
                'choice',
                [
                    'choices'     => Engine::$choicesArray,
                    'empty_value' => 'car.form.choice.empty_car_engine_type',
                    'empty_data'  => null,
                    'required'    => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_choose_car_engine_type']),
                    ],
                    'label' => 'car.form.label.engine',
                ]
            )
            ->add(
                'gearbox',
                'choice',
                [
                    'choices'     => Gearbox::$choicesArray,
                    'empty_value' => 'car.form.choice.empty_car_gearbox_type',
                    'empty_data'  => null,
                    'required'    => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_choose_car_gearbox_type']),
                    ],
                    'label' => 'car.form.label.gearbox',
                ]
            )
            ->add(
                'registrationCountry',
                'choice',
                [
                    'choices'     => Country::$choicesArray,
                    'empty_value' => 'car.form.choice.empty_car_registration_country',
                    'empty_data'  => null,
                    'required'    => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_choose_car_registration_country']),
                    ],
                    'label' => 'car.form.label.registrationCountry',
                ]
            )
            ->add(
                'prices',
                'collection',
                [
                    'type'           => new PriceType(),
                    'allow_add'      => true,
                    'allow_delete'   => true,
                    'by_reference'   => false,
                    'prototype'      => true,
                    'prototype_name' => '__price_name__',
                    'label'          => 'car.form.label.prices',
                    'error_bubbling' => false,
                    'constraints'    => [
                        new Valid(),
                    ],
                ]
            )
            ->add(
                'images',
                'collection',
                [
                    'type'           => new ImageType(),
                    'allow_add'      => true,
                    'allow_delete'   => true,
                    'by_reference'   => false,
                    'prototype'      => true,
                    'prototype_name' => '__image_name__',
                    'label'          => 'car.form.label.images',
                    'error_bubbling' => false,
                    'constraints'    => [
                        new Valid(),
                    ],
                ]
            )
            ->add(
                'promoteCarousel',
                'checkbox',
                [
                    'label' => 'car.form.label.promoteCarousel',
                ]
            )
            ->add(
                'promoteFrontpage',
                'checkbox',
                [
                    'label' => 'car.form.label.promoteFrontpage',
                ]
            )
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                [
                    'translatable_class' => 'Dende\\FrontBundle\\Entity\\Car',
                    'fields'             => [
                        'title' => [
                            'label' => 'car.form.label.title',
                        ],
                        'description' => [
                            'label' => 'car.form.label.description',
                        ],
                    ],
                    'label' => ' ',
                ]
            )
            ->add(
                'adminNotes',
                'textarea',
                [
                    'label' => 'car.form.label.adminNotes',
                ]
            )
            ->add(
                'hidden',
                'checkbox',
                [
                    'label' => 'car.form.label.hidden',
                ]
            );
    }

    public function getName()
    {
        return 'dende_form_car';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'Dende\FrontBundle\Entity\Car',
            'csrf_protection' => false,
        ]);
    }
}
