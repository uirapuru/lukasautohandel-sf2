<?php

namespace Dende\FrontBundle\Manager;

use Dende\FrontBundle\Entity\Car;

class CarManager extends TranslatableManager
{
    public function delete(Car $car)
    {
        $this->em->remove($car);
        $this->em->flush();
    }
}
