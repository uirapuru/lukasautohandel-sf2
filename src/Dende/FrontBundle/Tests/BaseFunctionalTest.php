<?php
namespace Dende\FrontBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Client;

abstract class BaseFunctionalTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Container
     */
    protected $container;

    public function setUp()
    {
        parent::setUp();

        $this->resetKernel();
        $this->prepareClient();

        $this->loadFixtures([
            "Dende\FrontBundle\DataFixtures\ORM\BrandData",
            "Dende\FrontBundle\DataFixtures\ORM\CarsData",
            "Dende\FrontBundle\DataFixtures\ORM\ColorsData",
            "Dende\FrontBundle\DataFixtures\ORM\CurrenciesData",
            "Dende\FrontBundle\DataFixtures\ORM\ImagesData",
            "Dende\FrontBundle\DataFixtures\ORM\ModelsData",
            "Dende\FrontBundle\DataFixtures\ORM\PricesData",
            "Dende\FrontBundle\DataFixtures\ORM\TypesData",
            "Dende\FrontBundle\DataFixtures\ORM\UsersData",
        ]);
    }

    public function tearDown()
    {
        static::$kernel->shutdown();
    }

    protected function prepareClient()
    {
        $this->client = static::makeClient(true);
        $this->client->followRedirects(true);
    }

    protected function resetKernel()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->container = static::$kernel->getContainer();
    }

    protected function getContent()
    {
        return $this->client->getResponse()->getContent();
    }

    protected function getStatusCode()
    {
        return $this->client->getResponse()->getStatusCode();
    }

    protected function resetDb()
    {
        $em     = $this->container->get('doctrine.orm.default_entity_manager');
        $root   = $this->container->getParameter('kernel.root_dir');
        $loader = new Loader();
        $loader->loadFromDirectory($root.'/../src/Dende/FrontBundle/DataFixtures/ORM');
        $purger = new ORMPurger($em);
//        $em->getConnection()->exec('SET FOREIGN_KEY_CHECKS = 0;');
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }
}
