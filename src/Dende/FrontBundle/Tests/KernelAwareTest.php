<?php
namespace Dende\FrontBundle\Tests;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

require_once dirname(__DIR__).'/../../../app/AppKernel.php';

abstract class KernelAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AppKernel
     */
    protected $kernel;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Container
     */
    protected $container;

    /**
     */
    public function setUp()
    {
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();

        parent::setUp();
    }

    /**
     */
    public function tearDown()
    {
        $this->kernel->shutdown();

        parent::tearDown();
    }
}
