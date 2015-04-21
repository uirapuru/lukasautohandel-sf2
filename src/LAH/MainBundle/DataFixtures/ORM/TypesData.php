<?php
namespace LAH\MainBundle\DataFixtures\ORM;

use Dende\CommonBundle\DataFixtures\BaseFixture;
use LAH\MainBundle\Entity\Type;

class TypesData extends BaseFixture
{
    public function getOrder()
    {
        return 1;
    }

    public function insert($params)
    {
        $type = new Type();
        $type->setName($params['name']);

        return $type;
    }
}
