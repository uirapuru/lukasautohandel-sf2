<?php
namespace LAH\FrontBundle\Tests\Unit\Form\Handler;

use LAH\AdminBundle\Form\Handler\ProcessModel;
use Mockery as m;

class ProcessModelsTest extends \PHPUnit_Framework_TestCase
{
    public function testRemoveUnusedWithFoundModel()
    {
        $brandMock = m::mock("LAH\MainBundle\Entity\Brand");

        $modelMock = m::mock("LAH\MainBundle\Entity\Model");
        $modelMock->shouldReceive('setBrand')->once();

        $carMock = m::mock("LAH\MainBundle\Entity\Car");
        $carMock->shouldReceive('setModel')->once();

        $brandRepoMock = m::mock("Doctrine\ORM\EntityRepository");
        $brandRepoMock->shouldReceive('findOneByName')->once()->andReturn($brandMock);

        $modelRepoMock = m::mock("Doctrine\ORM\EntityRepository");
        $modelRepoMock->shouldReceive('findOneByName')->once()->andReturn($modelMock);

        $entityManagerMock = m::mock("Doctrine\ORM\EntityManager");
        $entityManagerMock->shouldReceive('persist')->once();
        $entityManagerMock->shouldReceive('getRepository')->with('LAHMainBundle:Model')->andReturn($modelRepoMock);
        $entityManagerMock->shouldReceive('getRepository')->with('LAHMainBundle:Brand')->andReturn($brandRepoMock);

        $formMock = m::mock("Symfony\Component\Form\Form");
        $formMock->shouldReceive('get->get->getData')->andReturn('newTestModel');
        $formMock->shouldReceive('getData')->andReturn($carMock);

        $processModel = new ProcessModel();
        $processModel->setForm($formMock);
        $processModel->setEntityManager($entityManagerMock);
        $processModel->addModel();
    }

    public function testRemoveUnusedWithModelNotFound()
    {
        $brandMock = m::mock("LAH\MainBundle\Entity\Brand");

        $modelMock = m::mock("LAH\MainBundle\Entity\Model");
        $modelMock->shouldReceive('setBrand')->once();

        $carMock = m::mock("LAH\MainBundle\Entity\Car");
        $carMock->shouldReceive('setModel')->once();

        $brandRepoMock = m::mock("Doctrine\ORM\EntityRepository");
        $brandRepoMock->shouldReceive('findOneByName')->once()->andReturn($brandMock);

        $modelRepoMock = m::mock("Doctrine\ORM\EntityRepository");
        $modelRepoMock->shouldReceive('findOneByName')->once()->andReturn($modelMock);

        $entityManagerMock = m::mock("Doctrine\ORM\EntityManager");
        $entityManagerMock->shouldReceive('persist')->once();
        $entityManagerMock->shouldReceive('getRepository')->with('LAHMainBundle:Model')->andReturn($modelRepoMock);
        $entityManagerMock->shouldReceive('getRepository')->with('LAHMainBundle:Brand')->andReturn($brandRepoMock);

        $formMock = m::mock("Symfony\Component\Form\Form");
        $formMock->shouldReceive('get->get->getData')->andReturn('newTestType');
        $formMock->shouldReceive('get->getData')->andReturn($modelMock);
        $formMock->shouldReceive('getData')->andReturn($carMock);

        $processModel = new ProcessModel();
        $processModel->setForm($formMock);
        $processModel->setEntityManager($entityManagerMock);
        $processModel->addModel();
    }

    public function tearDown()
    {
        m::close();
    }
}
