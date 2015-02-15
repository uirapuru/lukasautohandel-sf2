<?php
namespace Dende\FrontBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Client;

class BaseFunctionalTest extends WebTestCase
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Container $container
     */
    protected $container;

    public function setUp()
    {
        $this->resetKernel();
        $this->prepareClient([
            'HTTP_HOST' => $this->container->getParameter('base_url'),
        ]);
        $this->resetDb();
    }

    protected function prepareClient(array $server = array())
    {
        $this->client = $this->container->get('test.client');
        $this->client->setServerParameters($server);
        $this->client->followRedirects(true);
    }

    protected function resetKernel()
    {
        if (null !== static::$kernel) {
            static::$kernel->shutdown();
        }
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
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        $loader = new Loader();
        $loader->loadFromDirectory('src/Dende/FrontBundle/DataFixtures/ORM');
        $purger = new ORMPurger($em);
        $em->getConnection()->exec('SET FOREIGN_KEY_CHECKS = 0;');
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }
}
