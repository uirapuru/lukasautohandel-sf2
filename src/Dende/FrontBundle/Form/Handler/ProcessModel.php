<?php
namespace Dende\FrontBundle\Form\Handler;

use Dende\FrontBundle\Entity\Brand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;

class ProcessModel
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

    public function addModel()
    {
        $car = $this->form->getData();
        $newModelName = $this->form->get("add_model")->get("name")->getData();

        if (!is_null($newModelName)) {
            $model = $this->entityManager->getRepository("FrontBundle:Model")->findOneByName($newModelName);

            if (is_null($model)) {
                $model = $this->form->get("add_model")->getData();
            }

            $newModelBrandName = $this->form->get("add_model")->get("brand")->getData();
            $brand = $this->entityManager->getRepository("FrontBundle:Brand")->findOneByName($newModelBrandName);

            if (is_null($brand)) {
                $brand = new Brand();
                $brand->setName($newModelBrandName);
                $this->entityManager->persist($brand);
            }

            $model->setBrand($brand);

            $car->setModel($model);
            $this->entityManager->persist($model);
        }
    }
}
