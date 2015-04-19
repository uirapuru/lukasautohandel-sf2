<?php
namespace LAH\MainBundle\DataFixtures\ORM;

use Dende\CommonBundle\DataFixtures\BaseFixture;
use LAH\MainBundle\Entity\Price;

class PricesData extends BaseFixture
{
    public function getOrder()
    {
        return 20;
    }

    public function insert($params)
    {
        $price = new Price();
        $price->setAmount($params['amount']);
        $price->setCar(
            $this->getReference($params['car'])
        );
        $price->setCurrency(
            $this->getReference($params['currency'])
        );

        return $price;
    }
}
