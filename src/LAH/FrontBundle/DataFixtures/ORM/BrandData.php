<?php
namespace LAH\FrontBundle\DataFixtures\ORM;

use Dende\CommonBundle\DataFixtures\BaseFixtures;
use Dende\CommonBundle\DataFixtures\FixtureInterface;
use LAH\FrontBundle\Entity\Brand;

class BrandData extends BaseFixture implements FixtureInterface
{
    public function getOrder()
    {
        return 0;
    }

    public function insert($params)
    {
        $brand = new Brand();
        $brand->setName($params['name']);

        return $brand;
    }
}
