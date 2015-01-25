<?php

namespace Dende\FrontBundle\Form\Type;

use Dende\FrontBundle\Dictionary\Country;
use Dende\FrontBundle\Dictionary\Engine;
use Dende\FrontBundle\Dictionary\Fuel;
use Dende\FrontBundle\Dictionary\Gearbox;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

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
                        new NotNull(['message' => 'validator.you_have_to_choose_car_type']),
                    ]
                ]
            )
            ->add(
                'model',
                "entity",
                [
                    "class" => "Dende\FrontBundle\Entity\Model",
                    "property" => "name",
                    'empty_value' => 'car.form.choice.empty_car_model',
                    'empty_data' => null,
                    'required' => true,
                    'constraints' => [
                        new NotNull(['message' => 'validator.you_have_to_choose_car_model']),
                    ]
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
                        new NotNull(['message' => 'validator.you_have_to_choose_car_color']),
                    ]
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
                            "minMessage" => "validator.production_year_too_small"
                        ]),
                        new Regex(["pattern" => "/\d\d\d\d/", "message" => "validator.production_year_format"])
                    ]
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
                            "minMessage" => "validator.distance_too_small"
                        ]),
                    ]
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
                    ]
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
                    ]
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
                    ]
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
                    ]
                ]
            )
//            ->add(
//                'prices',
//                "entity",
//                [
//                    "class" => "Dende\FrontBundle\Entity\Price",
//                    "multiple" => true,
//                    "expanded" => true,
//                    "property" => "amount"
//                ]
//            )
            ->add(
                'images',
                "collection",
                [
                    "type" => new ImageType(),
                    "allow_add" => true,
                    "allow_delete" => true,
                    "by_reference" => false,
                    "prototype" => true,
                    "prototype_name" => "__name__"
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

                ]
            )
            ->add(
                'promoteFrontpage',
                "checkbox",
                [

                ]
            )
            ->add(
                'title',
                "text",
                [
                    'required' => true,
                    'constraints' => [
                        new NotNull(["message" => "validator.title_cannot_be_empty"]),
                        new Length(["max" => 4096, "maxMessage" => "validator.title_max_length"])
                    ]
                ]
            )
            ->add(
                'description',
                "textarea",
                [
                    'required' => true,
                    'constraints' => [
                        new NotNull(["message" => "validator.description_cannot_be_empty"]),
                    ]
                ]
            )
            ->add(
                'adminNotes',
                "textarea",
                [

                ]
            )
            ->add(
                'hidden',
                "checkbox",
                [

                ]
            )
        ;
    }

    public function getName()
    {
        return 'dende_form_add_car';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dende\FrontBundle\Entity\Car',
            'csrf_protection' => false
        ));
    }
}
