<?php
namespace Dende\FrontBundle\Tests\Unit\Form\Handler;

use Dende\FrontBundle\Form\Handler\ProcessImages;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery as m;

class ProcessImagesTest extends \PHPUnit_Framework_TestCase
{
    public function testRemoveUnused()
    {
        $imageMock = m::mock("Dende\FrontBundle\Entity\Image");
        $imageMock->shouldReceive("setCar")->withArgs([null])->once();

        $carMock = m::mock("Dende\FrontBundle\Entity\Car");
        $carMock->shouldReceive("getImages->contains")->once()->andReturn(false);

        $originalImagesMock = new ArrayCollection([$imageMock]);

        $entityManagerMock = m::mock("Doctrine\ORM\EntityManager");
        $entityManagerMock->shouldReceive("remove")->once();

        $processImages = new ProcessImages();

        $processImages->setOriginalImages($originalImagesMock);
        $processImages->setEntityManager($entityManagerMock);
        $processImages->setCar($carMock);

        $processImages->removeUnused();
    }

    public function testProcessUploaded()
    {
        $uploadableManager = m::mock("Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager");
        $uploadableManager->shouldReceive("markEntityToUpload")->once();

        $imageMock = m::mock("Dende\FrontBundle\Entity\Image");
        $imageMock->shouldReceive("setCar")->once();
        $imageMock->shouldReceive("getFile")->twice()->andReturn(true);

        $carMock = m::mock("Dende\FrontBundle\Entity\Car");
        $carMock->shouldReceive("getImages")->once()->andReturn(new ArrayCollection([$imageMock]));

        $entityManagerMock = m::mock("Doctrine\ORM\EntityManager");
        $entityManagerMock->shouldReceive("persist")->once();

        $processImages = new ProcessImages();

        $processImages->setEntityManager($entityManagerMock);
        $processImages->setUploadableManager($uploadableManager);
        $processImages->setCar($carMock);

        $processImages->processUploaded();
    }

    public function tearDown()
    {
        m::close();
    }
}