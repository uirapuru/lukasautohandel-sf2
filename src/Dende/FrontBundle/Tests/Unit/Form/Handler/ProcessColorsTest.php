<?php
namespace Dende\FrontBundle\Tests\Unit\Form\Handler;

use Dende\FrontBundle\Entity\Color;
use Dende\FrontBundle\Form\Handler\ProcessColor;
use Mockery as m;

class ProcessColorsTest extends \PHPUnit_Framework_TestCase
{
    public function testRemoveUnusedWithFoundColor()
    {
        $colorMock = new Color();
        $colorMock->setName("test");

        $carMock = m::mock("Dende\FrontBundle\Entity\Car");
        $carMock->shouldReceive("setColor")->once();

        $entityManagerMock = m::mock("Doctrine\ORM\EntityManager");
        $entityManagerMock->shouldReceive("persist")->once();
        $entityManagerMock->shouldReceive("getRepository->findOneByName")->once()->andReturn($colorMock);

        $formMock = m::mock("Symfony\Component\Form\Form");
        $formMock->shouldReceive("get->get->getData")->once()->andReturn("newTestType");
        $formMock->shouldReceive("getData")->once()->andReturn($carMock);

        $processType = new ProcessColor();
        $processType->setForm($formMock);
        $processType->setEntityManager($entityManagerMock);
        $processType->addColor();
    }

    public function testRemoveUnusedWithColorNotFound()
    {
        $colorMock = new Color();
        $colorMock->setName("test");

        $carMock = m::mock("Dende\FrontBundle\Entity\Car");
        $carMock->shouldReceive("setColor")->once();

        $entityManagerMock = m::mock("Doctrine\ORM\EntityManager");
        $entityManagerMock->shouldReceive("persist")->once();
        $entityManagerMock->shouldReceive("getRepository->findOneByName")->once()->andReturnNull();

        $formMock = m::mock("Symfony\Component\Form\Form");
        $formMock->shouldReceive("get->get->getData")->once()->andReturn("newTestType");
        $formMock->shouldReceive("get->getData")->once()->andReturn($colorMock);
        $formMock->shouldReceive("getData")->once()->andReturn($carMock);

        $processType = new ProcessColor();
        $processType->setForm($formMock);
        $processType->setEntityManager($entityManagerMock);
        $processType->addColor();
    }

    public function tearDown()
    {
        m::close();
    }
}
