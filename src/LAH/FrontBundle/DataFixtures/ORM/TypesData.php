<?php
namespace LAH\FrontBundle\DataFixtures\ORM;

use Dende\CommonBundle\DataFixtures\BaseFixtures; use Dende\CommonBundle\DataFixtures\FixtureInterface;
use LAH\FrontBundle\Entity\Type;

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
