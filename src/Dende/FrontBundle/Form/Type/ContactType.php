<?php

namespace Dende\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('email', "email", array(
                    "label" => "contact.form.labels.email",
                    "required" => true,
                    "constraints" => array(
                        new Email(array(
                           "message" => "contact.wrong_email",
                        )),
                        new NotBlank(array(
                            "message" => "contact.empty_email",
                        )),
                    ),
                    "error_bubbling" => true,
                ))
                ->add('message', "textarea", array(
                    "label" => "contact.form.labels.message",
                    "required" => true,
                    "constraints" => array(
                        new NotBlank(array(
                            "message" => 'contact.empty_message',
                        )),
                        new Length(array(
                            "min" => 10,
                            "minMessage" => 'contact.too_short_message',
                            "max" => 1000,
                            "maxMessage" => 'contact.too_long_message',
                        )),
                    ),
                    "error_bubbling" => true,
                ))
                ->add('submit', "submit", array(
                    "label" => 'contact.form.labels.submit',
                ))
        ;
    }

    public function getName()
    {
        return 'contact_form';
    }
}
