<?php
namespace LAH\FrontBundle\DataFixtures\ORM;

use Dende\CommonBundle\DataFixtures\BaseFixtures;
use Dende\CommonBundle\DataFixtures\FixtureInterface;
use LAH\FrontBundle\Entity\Price;

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
