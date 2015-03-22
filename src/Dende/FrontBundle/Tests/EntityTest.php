<?php
namespace Dende\FrontBundle\Tests;

use Dende\FrontBundle\Entity\Car;
use Dende\FrontBundle\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class EntityTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        $this->markTestSkipped();
        $request = new Request();
        $request->setDefaultLocale("pl_pl");

        $this->client = $this->createClient();
        $this->container = $this->client->getContainer();
        $this->container->set("request", $request);
    }

    public function testInsertEntity()
    {
        $this->markTestSkipped();
        $car = $this->prepareCar();
        $entityManager = $this->container->get("doctrine.orm.default_entity_manager");

        /*
         * @var CarRepository
         */
        $repository = $entityManager->getRepository("FrontBundle:Car");

        $repository->setDefaultLocale("pl");

        $car->setTitle("polski tytul");
        $car->setDescription("polski desc");

        $entityManager->persist($car);

        $repository->setDefaultLocale("en");

        $car->setTitle("ang tytul");
        $car->setDescription("ang desc");

        $entityManager->persist($car);

        $entityManager->flush();
    }

    private function prepareCar()
    {
        $car = new Car();
        $car->setYear(2000);
        $car->setDistance(40000);
        $car->setFuel("diesel");
        $car->setEngine("diesel");
        $car->setGearbox("automat");
        $car->setRegistrationCountry("pl");
        $car->setPromoteCorousel(true);
        $car->setPromoteFrontpage(true);
        $car->setAdminNotes("notes");
        $car->setHidden(false);

        return $car;
    }
}
