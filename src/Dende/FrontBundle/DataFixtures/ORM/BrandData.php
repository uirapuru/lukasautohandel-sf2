<?php
namespace Dende\FrontBundle\DataFixtures\ORM;

use Dende\FrontBundle\DataFixtures\BaseFixture;
use Dende\FrontBundle\Entity\Brand;

class BrandData extends BaseFixture
{
    public function getOrder()
    {
        return 0;
    }

    public function insert($params)
    {
        $brand = new Brand();
        $brand->setName($params["name"]);

        return $brand;
    }
}