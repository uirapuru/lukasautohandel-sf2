<?php
namespace Dende\FrontBundle\Entity\Factory;

use Dende\FrontBundle\Entity\Type;

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
