<?php
namespace Dende\FrontBundle\DataFixtures\ORM;

use Dende\FrontBundle\DataFixtures\BaseFixture;
use Dende\FrontBundle\Entity\Image;
use Dende\FrontBundle\Event\Listener\GenerateThumbnailListener;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImagesData extends BaseFixture
{
    public function getOrder()
    {
        return 20;
    }

    public function insert($params)
    {
        $image = new Image();
        $image->setHidden($params["hidden"]);
        $image->setCar(
            $this->getReference($params["car"])
        );

        $name = md5(microtime()) . ".jpg";

        $destination = realpath(__DIR__."/../../../../../web/uploads/")."/";

        copy(realpath(__DIR__."/../../Resources/tests/test_image.jpg"), $destination.$name);

        $image->setPath($name);
        $image->setName($name);

        $listener = new GenerateThumbnailListener();
        $listener->processImage($image, $destination);

        return $image;
    }
}
