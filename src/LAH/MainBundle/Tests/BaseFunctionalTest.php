<?php
namespace LAH\MainBundle\Tests;

use Dende\CommonBundle\Tests\BaseFunctionalTest as BaseTest;

abstract class BaseFunctionalTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures([
            "LAH\MainBundle\DataFixtures\ORM\BrandData",
            "LAH\MainBundle\DataFixtures\ORM\CarsData",
            "LAH\MainBundle\DataFixtures\ORM\ColorsData",
            "LAH\MainBundle\DataFixtures\ORM\CurrenciesData",
            "LAH\MainBundle\DataFixtures\ORM\ImagesData",
            "LAH\MainBundle\DataFixtures\ORM\ModelsData",
            "LAH\MainBundle\DataFixtures\ORM\PricesData",
            "LAH\MainBundle\DataFixtures\ORM\TypesData",
            "LAH\MainBundle\DataFixtures\ORM\UsersData",
        ]);
    }
}
