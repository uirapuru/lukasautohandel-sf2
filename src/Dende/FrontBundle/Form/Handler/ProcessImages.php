<?php
namespace Dende\FrontBundle\Form\Handler;

use Dende\FrontBundle\Entity\Car;
use Dende\FrontBundle\Entity\Image;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;

class ProcessImages {

    /**
     * @var UploadableManager
     */
    private $uploadableManager;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Car
     */
    private $car;

    /**
     * @var Collection
     */
    private $originalImages;

    /**
     * @param UploadableManager $uploadableManager
     */
    public function setUploadableManager(UploadableManager $uploadableManager)
    {
        $this->uploadableManager = $uploadableManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Car $car
     */
    public function setCar(Car $car)
    {
        $this->car = $car;
    }

    /**
     * @param Collection $originalImages
     */
    public function setOriginalImages(Collection $originalImages)
    {
        $this->originalImages = clone($originalImages);
    }

    public function removeUnused()
    {
        foreach ($this->originalImages as $image) {
            /**
             * @var Image $image
             */
            if (!$this->car->getImages()->contains($image)) {
                $image->setCar(null);
                $this->entityManager->remove($image);
            }
        }
    }

    public function processUploaded()
    {
        $images = $this->car->getImages();

        foreach ($images as $image) {
            $image->setCar($this->car);

            if ($image->getFile() !== null) {
                $this->uploadableManager->markEntityToUpload($image, $image->getFile());
                $this->entityManager->persist($image);
            }
        }
    }
}