<?php
namespace LAH\FrontBundle\DataFixtures\ORM;

use LAH\FrontBundle\DataFixtures\BaseFixture;
use LAH\FrontBundle\Entity\Brand;

class BrandData extends BaseFixture
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
