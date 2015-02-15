<?php
namespace Dende\FrontBundle\Event\Listener;

use Dende\FrontBundle\Entity\Image;
use Gedmo\Uploadable\Event\UploadablePostFileProcessEventArgs;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class GenerateThumbnailListener
{
    /**
     * @param UploadablePostFileProcessEventArgs $args
     */
    public function uploadablePostFileProcess(UploadablePostFileProcessEventArgs $args)
    {
        $listener = $args->getListener();
        /**
         * @var Image $image
         */
        $image = $args->getEntity();
        $defaultPath = $listener->getDefaultPath();

        $this->processImage($image, $defaultPath);
    }

    public function processImage(Image $image, $defaultPath = '') {
        if ($defaultPath == null) {
           throw new \Exception("No default path");
        }

        $imagine = new Imagine();

        $size = new Box(200, 200);
        $mode = ImageInterface::THUMBNAIL_INSET;

        $imagine->open($defaultPath.DIRECTORY_SEPARATOR.$image->getName())
            ->thumbnail($size, $mode)
            ->save(implode(DIRECTORY_SEPARATOR, [$defaultPath, "thumbnails", $image->getName()]))
        ;
    }
}
