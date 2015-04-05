<?php

namespace Dende\FrontBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;

class ProcessColor
{
    /**
     * @var Form
     */
    private $form;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param Form $form
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addColor()
    {
        $car = $this->form->getData();

        $newColorName = $this->form->get('add_color')->get('translations')->get('pl')->get('name')->getData();

        if (!is_null($newColorName)) {
            $color = $this->entityManager->getRepository('FrontBundle:Color')->findOneByName($newColorName);

            if (is_null($color)) {
                $color = $this->form->get('add_color')->getData();
            }

            $car->setColor($color);
            $this->entityManager->persist($color);
        }
    }
}
