<?php
namespace LAH\MainBundle\DataFixtures\ORM;

use Dende\CommonBundle\DataFixtures\BaseFixture;
use LAH\MainBundle\Entity\Model;

class ModelsData extends BaseFixture
{
    public function getOrder()
    {
        return 1;
    }

    public function insert($params)
    {
        $model = new Model();
        $model->setName($params['name']);
        $model->setBrand(
            $this->getReference($params['brand'])
        );

        return $model;
    }
}
