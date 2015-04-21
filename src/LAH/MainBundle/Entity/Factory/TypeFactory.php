<?php
namespace LAH\MainBundle\Entity\Factory;

use LAH\MainBundle\Entity\Type;

class TypeFactory
{
    /**
     * @param $array
     *
     * @return Type
     */
    public function create(array $array = [])
    {
        $type = new Type();
        $type->setName($array['name']);

        return $type;
    }
}
