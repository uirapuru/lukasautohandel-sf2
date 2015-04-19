<?php
namespace LAH\SearchBundle\Service;

use LAH\FrontBundle\Entity\Brand;
use LAH\FrontBundle\Entity\Model;
use LAH\FrontBundle\Entity\Type;
use LAH\SearchBundle\Model\SearchQuery;
use Doctrine\ORM\QueryBuilder;

class SearchQueryModifier
{
    public function modify(SearchQuery $searchQuery, QueryBuilder $qb, &$cacheId = null)
    {
        $type = $searchQuery->getType();
        if ($type instanceof Type) {
            $qb->andWhere('c.type = :type');
            $qb->setParameter('type', $type);
            $cacheId[] = $type->getId();
        }

        $brand = $searchQuery->getBrand();
        if ($brand instanceof Brand) {
            $qb->innerJoin('c.model', 'm');

            $qb->andWhere('m.brand = :brand');
            $qb->setParameter('brand', $brand);
            $cacheId[] = $brand->getId();
        }

        $model = $searchQuery->getModel();
        if ($model instanceof Model) {
            $qb->andWhere('c.model = :carModel');
            $qb->setParameter('carModel', $model);
            $cacheId[] = $model->getId();
        }
    }
}
