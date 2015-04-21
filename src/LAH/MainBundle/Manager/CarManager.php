<?php
namespace LAH\MainBundle\Manager;

use LAH\MainBundle\Manager\TranslatableManager;
use LAH\MainBundle\Entity\Car;

class CarManager extends TranslatableManager
{
    public function delete(Car $car)
    {
        $this->em->remove($car);
        $this->em->flush();
    }
}
