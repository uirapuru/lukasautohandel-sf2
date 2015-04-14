<?php
namespace LAH\FrontBundle\Tests\Unit\Form\Handler;

use LAH\FrontBundle\Entity\Type;
use LAH\FrontBundle\Form\Handler\ProcessType;
use Mockery as m;

class ProcessTypesTest extends \PHPUnit_Framework_TestCase
{
    public function testRemoveUnusedWithFoundType()
    {
        $typeMock = new Type();
        $typeMock->setName('test');

        $carMock = m::mock("LAH\FrontBundle\Entity\Car");
        $carMock->shouldReceive('setType')->once();

        $entityManagerMock = m::mock("Doctrine\ORM\EntityManager");
        $entityManagerMock->shouldReceive('persist')->once();
        $entityManagerMock->shouldReceive('getRepository->findOneByName')->once()->andReturn($typeMock);

        $formMock = m::mock("Symfony\Component\Form\Form");
        $formMock->shouldReceive('get->get->getData')->once()->andReturn('newTestType');
        $formMock->shouldReceive('getData')->once()->andReturn($carMock);

        $processType = new ProcessType();
        $processType->setForm($formMock);
        $processType->setEntityManager($entityManagerMock);
        $processType->addType();
    }

    public function testRemoveUnusedWithTypeNotFound()
    {
        $typeMock = new Type();
        $typeMock->setName('test');

        $carMock = m::mock("LAH\FrontBundle\Entity\Car");
        $carMock->shouldReceive('setType')->once();

        $entityManagerMock = m::mock("Doctrine\ORM\EntityManager");
        $entityManagerMock->shouldReceive('persist')->once();
        $entityManagerMock->shouldReceive('getRepository->findOneByName')->once()->andReturnNull();

        $formMock = m::mock("Symfony\Component\Form\Form");
        $formMock->shouldReceive('get->get->getData')->once()->andReturn('newTestType');
        $formMock->shouldReceive('get->getData')->once()->andReturn($typeMock);
        $formMock->shouldReceive('getData')->once()->andReturn($carMock);

        $processType = new ProcessType();
        $processType->setForm($formMock);
        $processType->setEntityManager($entityManagerMock);
        $processType->addType();
    }

    public function tearDown()
    {
        m::close();
    }
}
