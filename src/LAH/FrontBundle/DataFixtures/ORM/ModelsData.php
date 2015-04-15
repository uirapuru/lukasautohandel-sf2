<?php
namespace LAH\FrontBundle\DataFixtures\ORM;

use Dende\CommonBundle\DataFixtures\BaseFixtures;
use Dende\CommonBundle\DataFixtures\FixtureInterface;
use LAH\FrontBundle\Entity\Model;

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
