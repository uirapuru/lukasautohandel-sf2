<?php

namespace Dende\FrontBundle\Form\Type;

use Dende\FrontBundle\Entity\Brand;
use Dende\FrontBundle\Entity\Model;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
                'entity',
                [
                    'class' => "Dende\FrontBundle\Entity\Type",
                    'property' => 'name',
                    'empty_value' => 'car.form.choice.all_types',
                    'empty_data' => null,
                    'required' => false,
                    'constraints' => [],
                    'label' => 'car.form.label.type',
                ]
            )
            ->add(
                'brand',
                'entity',
                [
                    'class' => "Dende\FrontBundle\Entity\Brand",
                    'empty_value' => 'car.form.choice.all_brands',
                    'empty_data' => null,
                    'property' => 'getName',
                    'required' => false,
                    'constraints' => [],
                    'label' => 'car.form.label.model',
                ]
            )
        ;

        $formModifier = function (FormInterface $form, $brand) {
            $form->add(
                'model',
                'entity',
                [
                    'class' => "Dende\FrontBundle\Entity\Model",
                    'empty_value' => 'car.form.choice.all_models',
                    'empty_data' => null,
                    'property' => 'getFullName',
                    'required' => false,
                    'query_builder' => function (EntityRepository $repo) use ($brand) {
                        $qb = $repo->createQueryBuilder('m');
                        if ($brand) {
                            $qb->where('m.brand = :brand');
                            $qb->setParameter('brand', $brand);
                        }

                        return $qb;
                    },
                    'constraints' => [],
                    'label' => 'car.form.label.model',
                ]
            );
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $searchQuery = $event->getData();
                $formModifier($event->getForm(), $searchQuery->getBrand());
            }
        );

        $builder->get('brand')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $brand = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $brand);
            }
        );
    }

    public function getName()
    {
        return 'dende_form_search';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Dende\FrontBundle\Model\SearchQuery',
            'csrf_protection' => true,
        ]);
    }
}
