<?php
namespace LAH\FrontBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
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

    protected function resetDb()
    {
        $em     = $this->container->get('doctrine.orm.default_entity_manager');
        $root   = $this->container->getParameter('kernel.root_dir');
        $loader = new Loader();
        $loader->loadFromDirectory($root.'/../src/LAH/FrontBundle/DataFixtures/ORM');
        $purger = new ORMPurger($em);
//        $em->getConnection()->exec('SET FOREIGN_KEY_CHECKS = 0;');
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }
}
