<?php
namespace LAH\MainBundle\Repository;

use Doctrine\ORM\Query;

class ColorRepository extends TranslatableRepository
{
    public function findOneByName($name)
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.name = :name')
            ->setParameter('name', $name)
            ->getQuery();

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getOneOrNullResult();
    }
}
