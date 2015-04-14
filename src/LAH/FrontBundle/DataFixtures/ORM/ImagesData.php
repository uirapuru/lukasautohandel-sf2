<?php
namespace LAH\FrontBundle\DataFixtures\ORM;

use LAH\FrontBundle\DataFixtures\BaseFixture;
use LAH\FrontBundle\Entity\Image;
use LAH\FrontBundle\Event\Listener\GenerateThumbnailListener;

class ImagesData extends BaseFixture
{
    public function getOrder()
    {
        return 20;
    }

    public function insert($params)
    {
        $image = new Image();
        $image->setHidden($params['hidden']);
        $image->setCar(
            $this->getReference($params['car'])
        );

        $name = md5(microtime()).'.jpg';

        $destination = realpath(__DIR__.'/../../../../../web/uploads').'/';

        $images = [
            'test_image.jpg',
            'test_image_02.jpg',
            'test_image_03.jpg',
            'test_image_04.jpg',
            'test_image_05.jpg',
            'test_image_06.jpg',
        ];

        copy(realpath(__DIR__.'/../../Resources/tests/'.$images[rand(0, count($images) - 1)]), $destination.$name);

        $image->setPath($name);
        $image->setName($name);

        $listener = new GenerateThumbnailListener();
        $listener->processImage($image, $destination);

        return $image;
    }
}
