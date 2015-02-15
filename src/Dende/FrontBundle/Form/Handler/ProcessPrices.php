<?php
namespace Dende\FrontBundle\Form\Handler;

use Dende\FrontBundle\Entity\Car;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;

class ProcessPrices
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Collection
     */
    private $originalPrices;

    /**
     * @var Car
     */
    private $car;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Car $car
     */
    public function setCar(Car $car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @param Collection $originalPrices
     */
    public function setOriginalPrices(Collection $originalPrices)
    {
        $this->originalPrices = clone($originalPrices);
    }

    public function removeUnused()
    {
        foreach ($this->originalPrices as $price) {
            /**
             * @var Price $price
             */
            if (!$this->car->getPrices()->contains($price)) {
                $price->setCar(null);
                $this->entityManager->remove($price);
            }
        }
    }
}
