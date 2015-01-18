<?php
namespace Dende\FrontBundle\DataFixtures\ORM;

use Dende\FrontBundle\DataFixtures\BaseFixture;
use Dende\FrontBundle\Entity\Image;
use Dende\FrontBundle\Entity\Type;

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

        return $image;
    }
}