<?php

namespace Dende\FrontBundle\Form\Type;

use Dende\FrontBundle\Dictionary\Country;
use Dende\FrontBundle\Dictionary\Engine;
use Dende\FrontBundle\Dictionary\Fuel;
use Dende\FrontBundle\Dictionary\Gearbox;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
                    "property" => "name"
                ]
            )
            ->add(
                'model',
                "entity",
                [
                    "class" => "Dende\FrontBundle\Entity\Model",
                    "property" => "name"
                ]
            )
            ->add(
                'color',
                "entity",
                [
                    "class" => "Dende\FrontBundle\Entity\Color",
                    "property" => "name"
                ]
            )
            ->add(
                'year',
                "integer"
            )
            ->add(
                'distance',
                "integer"
            )
            ->add(
                'fuel',
                "choice",
                [
                    "choices" => Fuel::$choicesArray
                ]
            )
            ->add(
                'engine',
                "choice",
                [
                    "choices" => Engine::$choicesArray
                ]
            )
            ->add(
                'gearbox',
                "choice",
                [
                    "choices" => Gearbox::$choicesArray
                ]
            )
            ->add(
                'registrationCountry',
                "choice",
                [
                    "choices" => Country::$choicesArray
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
//            ->add(
//                'image',
//                "entity",
//                [
//                    "class" => "Dende\FrontBundle\Entity\Image",
//                    "multiple" => true,
//                    "expanded" => true,
//                    "property" => ""
//                ]
//            )
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

                ]
            )
            ->add(
                'description',
                "textarea",
                [

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



            ->add('submit', "submit", [])
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
