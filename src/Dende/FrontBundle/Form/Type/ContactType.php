<?php
namespace Dende\FrontBundle\Form\Type;

use Dende\FrontBundle\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    /**
     * @var Car
     */
    private $car;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var Router
     */
    private $router;

    /**
     * @param Car $car
     */
    public function setCar($car)
    {
        $this->car = $car;
    }

    public function __construct(Translator $translator, Router $router)
    {
        $this->translator = $translator;
        $this->router     = $router;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->car) {
            $carId = $this->car->getId();

            $builder
                ->add('subject', 'text', [
                    'label'          => 'contact.form.labels.subject',
                    'required'       => false,
                    'error_bubbling' => true,
                    'attr'           => [
                        'placeholder' => $this->translator->trans('contact.form.placeholder.subject'),
                    ],
                    'data' => $this->translator->trans(
                        'contact.message.about_car',
                        [
                            '%title%'  => $this->car->getTitle(),
                            '%link%'   => $this->router->generate('show', [
                                'id'   => $this->car->getId(),
                                'slug' => $this->car->getSlug(),
                            ], true),
                        ]
                    ),
                ]);
        }

        $builder
            ->add('name', 'text', [
                'label'          => 'contact.form.labels.name',
                'required'       => false,
                'error_bubbling' => true,
                'attr'           => [
                    'placeholder' => $this->translator->trans('contact.form.placeholder.name'),
                ],
            ])
            ->add('email', 'email', [
                'label'       => 'contact.form.labels.email',
                'required'    => true,
                'constraints' => [
                    new Email([
                       'message' => 'contact.wrong_email',
                    ]),
                    new NotBlank([
                        'message' => 'contact.empty_email',
                    ]),
                ],
                'error_bubbling' => true,
                'attr'           => [
                    'placeholder' => $this->translator->trans('contact.form.placeholder.email'),
                ],
            ])
            ->add('phone', 'text', [
                'label'          => 'contact.form.labels.phone',
                'required'       => false,
                'error_bubbling' => true,
                'attr'           => [
                    'placeholder' => $this->translator->trans('contact.form.placeholder.phone'),
                ],
            ])
            ->add('message', 'textarea', [
                'label'       => 'contact.form.labels.message',
                'required'    => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'contact.empty_message',
                    ]),
                    new Length([
                        'min'        => 10,
                        'minMessage' => 'contact.too_short_message',
                        'max'        => 1000,
                        'maxMessage' => 'contact.too_long_message',
                    ]),
                ],
                'error_bubbling' => true,
                'attr'           => [
                    'placeholder' => $this->translator->trans('contact.form.placeholder.message'),
                ],
            ])
            ->add('submit', 'submit', [
                'label' => 'contact.form.labels.submit',
            ]);

        $options["action"] = $this->router->generate("contact", ["id" => isset($carId) ? $carId : null]);
    }

    public function getName()
    {
        return 'dende_form_contact';
    }
}
