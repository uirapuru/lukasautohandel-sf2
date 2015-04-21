<?php
namespace LAH\MainBundle\DataFixtures\ORM;

use Dende\CommonBundle\DataFixtures\BaseFixture;
use LAH\MainBundle\Entity\Brand;

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
