<?php
namespace LAH\FrontBundle\Entity\Factory;

use LAH\FrontBundle\Entity\Type;

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
