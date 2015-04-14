<?php
namespace LAH\FrontBundle\Manager;

use LAH\FrontBundle\Entity\Car;

class CarManager extends TranslatableManager
{
    public function delete(Car $car)
    {
        $this->em->remove($car);
        $this->em->flush();
    }
}
