<?php
namespace LAH\AdminBundle\Event\Listener;

use Mockery as m;

trait UploadedImageListenerTrait
{
    private function getEntityMock($times = 1)
    {
        $entityMock = m::mock('LAH\MainBundle\Entity\Image');
        $entityMock->shouldReceive('getName')->times($times)->andReturn('some_name.jpg');

        return $entityMock;
    }

    private function getArgsMock($entityMock, $listenerMock)
    {
        $argsMock = m::mock('Gedmo\Uploadable\Event\UploadablePostFileProcessEventArgs');
        $argsMock->shouldReceive('getListener')->once()->andReturn($listenerMock);
        $argsMock->shouldReceive('getEntity')->once()->andReturn($entityMock);

        return $argsMock;
    }

    public function tearDown()
    {
        m::close();
    }
}
