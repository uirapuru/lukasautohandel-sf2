<?php
namespace Dende\FrontBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;

class ProcessType
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

    public function addType()
    {
        $car = $this->form->getData();

        $newTypeName = $this->form->get('add_type')->get('name')->getData();

        if (!is_null($newTypeName)) {
            $type = $this->entityManager->getRepository('FrontBundle:Type')->findOneByName($newTypeName);

            if (is_null($type)) {
                $type = $this->form->get('add_type')->getData();
            }

            $car->setType($type);
            $this->entityManager->persist($type);
        }
    }
}
