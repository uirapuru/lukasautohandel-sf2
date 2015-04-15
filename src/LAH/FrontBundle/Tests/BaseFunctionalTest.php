<?php
namespace LAH\FrontBundle\Tests;

use Dende\CommonBundle\Tests\BaseFunctionalTest as BaseTest;

abstract class BaseFunctionalTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures([
            "LAH\FrontBundle\DataFixtures\ORM\BrandData",
            "LAH\FrontBundle\DataFixtures\ORM\CarsData",
            "LAH\FrontBundle\DataFixtures\ORM\ColorsData",
            "LAH\FrontBundle\DataFixtures\ORM\CurrenciesData",
            "LAH\FrontBundle\DataFixtures\ORM\ImagesData",
            "LAH\FrontBundle\DataFixtures\ORM\ModelsData",
            "LAH\FrontBundle\DataFixtures\ORM\PricesData",
            "LAH\FrontBundle\DataFixtures\ORM\TypesData",
            "LAH\FrontBundle\DataFixtures\ORM\UsersData",
        ]);
    }
}
