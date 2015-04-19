<?php
namespace LAH\FrontBundle\Tests\Unit\Form\Handler;

use LAH\AdminBundle\Form\Handler\ProcessPrices;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery as m;

class ProcessPricesTest extends \PHPUnit_Framework_TestCase
{
    public function testRemoveUnused()
    {
        $priceMock = m::mock("LAH\MainBundle\Entity\Price");
        $priceMock->shouldReceive('setCar')->withArgs([null])->once();

        $carMock = m::mock("LAH\MainBundle\Entity\Car");
        $carMock->shouldReceive('getPrices->contains')->once()->andReturn(false);

        $originalPricesMock = new ArrayCollection([$priceMock]);

        $entityManagerMock = m::mock("Doctrine\ORM\EntityManager");
        $entityManagerMock->shouldReceive('remove')->once();

        $processImages = new ProcessPrices();

        $processImages->setOriginalPrices($originalPricesMock);
        $processImages->setEntityManager($entityManagerMock);
        $processImages->setCar($carMock);

        $processImages->removeUnused();
    }

    public function tearDown()
    {
        m::close();
    }
}
