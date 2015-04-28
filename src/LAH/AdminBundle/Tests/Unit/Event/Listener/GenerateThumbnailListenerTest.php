<?php
namespace LAH\AdminBundle\Tests\Unit\Event\Listener;

use LAH\AdminBundle\Event\Listener\GenerateThumbnailListener;
use LAH\AdminBundle\Event\Listener\UploadedImageListenerTrait;
use Mockery as m;

class GenerateThumbnailListenerTest extends \PHPUnit_Framework_TestCase
{
    use UploadedImageListenerTrait;

    /**
     * @expectedException \Imagine\Exception\InvalidArgumentException
     * @expectedExceptionMessage File /tmp/some_name.jpg does not exist
     */
    public function testUploadablePostFileProcessNoFileException()
    {
        $entityMock = $this->getEntityMock();

        $listenerMock = m::mock();
        $listenerMock->shouldReceive('getDefaultPath')->andReturn('/tmp');

        $argsMock = $this->getArgsMock($entityMock, $listenerMock);

        $listener = new GenerateThumbnailListener();
        $listener->uploadablePostFileProcess($argsMock);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage No default path
     */
    public function testUploadablePostFileProcessNoDefaultPathException()
    {
        $entityMock = $this->getEntityMock(0);

        $listenerMock = m::mock();
        $listenerMock->shouldReceive('getDefaultPath')->andReturn(null);

        $argsMock = $this->getArgsMock($entityMock, $listenerMock);

        $listener = new GenerateThumbnailListener();
        $listener->uploadablePostFileProcess($argsMock);
    }
}
